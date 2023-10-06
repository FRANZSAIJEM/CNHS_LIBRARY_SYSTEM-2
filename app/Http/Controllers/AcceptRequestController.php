<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;

use App\Models\Reply;
use App\Models\Book; // Import your Book model
use App\Models\AcceptedRequest; // Import your Book model
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;



class AcceptRequestController extends Controller
{
    public function acceptRequest(Request $request, User $user, Book $book)
    {
        // Assuming you have a "accepted_requests" table to store the accepted requests.
        $acceptedRequest = new AcceptedRequest();
        $acceptedRequest->user_id = $user->id;
        $acceptedRequest->book_id = $book->id;
        $acceptedRequest->book_title = $book->title;
        $acceptedRequest->borrower_id = $user->id;
        $acceptedRequest->date_borrow = now();
         // Retrieve the values from the form and format them as datetime values
        $acceptedRequest->date_pickup = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_pickup'));
        $acceptedRequest->date_return = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_return'));

         $currentDate = \Carbon\Carbon::now();
        $fines = $request->input('fines');
        if ($acceptedRequest->date_return->isFuture()) {
            $acceptedRequest->fines = 0.00; // Set fines to 0.00 if return date is in the future
        } else {
            $fines = $request->input('fines');
            if (!empty($fines)) {
                // Calculate the fines and store them with two decimal places
                $calculatedFines = (float) number_format($fines, 2, '.', '');
                $acceptedRequest->fines = $calculatedFines;

                                // Update the timestamp to the current time
                $acceptedRequest->updated_at = now();

                // Store the calculated fines in the session
                $request->session()->put('fines', $calculatedFines);

                // Store the calculated fines in the session
                $request->session()->put('fines', $calculatedFines);
            } else {
                $acceptedRequest->fines = 0.00; // If no fines are provided, set it to null.
            }
        }
        $acceptedRequest->save();

        // Detach the book from the user's requestedBooks relationship since it's been accepted.
        $user->requestedBooks()->detach($book);

        $notificationText = "{$user->name} Borrowed '{$book->title}' on " . now()->format('Y-m-d H:i A') . ".";

        $notification = new Notification([
            'user_id' => $user->id,
            'notification_text' => $notificationText,
        ]);
        $notification->save();

        $userIdsToNotify = User::pluck('id')->toArray();

        $usersToNotify = User::whereIn('id', $userIdsToNotify)->get();

        foreach ($usersToNotify as $userToNotify) {
            $userNotification = new UserNotification([
                'user_id' => $userToNotify->id,
                'notification_id' => $notification->id,
            ]);
            $userNotification->save();
        }

        // Redirect back to the previous page or wherever you want.
        return redirect()->back()->with('success', 'Accepted and saved.')
        ->with('notification', $notificationText);
    }


    public function transactions(Request $request)
    {
        $idNumberSearch = $request->input('id_number_search');
        // Retrieve all accepted requests from the database
        $query = AcceptedRequest::with('user');

        // If there is an ID number search query, filter by it
        if (!empty($idNumberSearch)) {
            $query->whereHas('user', function ($q) use ($idNumberSearch) {
                $q->where('id_number', 'LIKE', "%$idNumberSearch%")
                  ->orWhere('name', 'LIKE', "%$idNumberSearch%");
            });
        }

        $acceptedRequests = $query->get();

        // Convert date_borrow and date_return fields to DateTime objects
        $acceptedRequests->each(function ($acceptedRequest) {
            $acceptedRequest->date_borrow = \Carbon\Carbon::parse($acceptedRequest->date_borrow);
            $acceptedRequest->date_pickup = \Carbon\Carbon::parse($acceptedRequest->date_pickup);
            $acceptedRequest->date_return = \Carbon\Carbon::parse($acceptedRequest->date_return);
        });

        // Remove the search query parameter to clear the search
        $request->merge(['id_number_search' => null]);

        return view('transactions', compact('acceptedRequests', 'idNumberSearch'));
    }


    public function history()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Retrieve the user's notifications from the database
        $userNotifications = UserNotification::where('user_id', $user->id)
            ->with('notification')
            ->get();

        return view('history', compact('userNotifications'));
    }



    public function clearNotification($id)
    {
        // Find the UserNotification record by ID
        $userNotification = UserNotification::find($id);

        // Check if the record exists
        if ($userNotification) {
            // Delete the UserNotification record
            $userNotification->delete();

            // Redirect back to the history page or wherever you prefer
            return redirect()->back()->with('success', 'Cleared successfully');
        } else {
            // Handle the case where the record does not exist (e.g., show an error message)
            return redirect()->back()->with('error', 'Notification not found');
        }
    }

    public function destroy($id){
        $transaction = AcceptedRequest::find($id);

        $transaction->delete();

        return redirect()->back()->with('success', 'Returned successfully');

    }


    public function notifications()
    {
        // Get the ID of the logged-in user
        $loggedInUserId = auth()->id();

        // Retrieve comments with the same user_id as the logged-in user
        $comments = Comment::where('user_id', $loggedInUserId)->get();

        // Retrieve replies associated with these comments, excluding the user's own replies
        $replies = $comments->flatMap(function ($comment) use ($loggedInUserId) {
            return $comment->replies->where('user_id', '!=', $loggedInUserId);
        });

          // Retrieve replies associated with these comments, excluding the user's own replies
          $likes = $comments->flatMap(function ($comment) use ($loggedInUserId) {
            return $comment->likes->where('user_id', '!=', $loggedInUserId);
        });


        // Retrieve accepted requests for the logged-in user
        $acceptedRequests = AcceptedRequest::where('user_id', $loggedInUserId)->get();

        // Retrieve book information for each comment
        $commentsWithBooks = $comments->map(function ($comment) {
            $book = $comment->book;
            return [
                'comment' => $comment,
                'book' => $book,
            ];
        });

        return view('notifications', [
            'acceptedRequests' => $acceptedRequests,
            'replies' => $replies,
            'likes' => $likes,
            'loggedInUser' => auth()->user(),
            'commentsWithBooks' => $commentsWithBooks,
        ]);
    }





}
