<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;
use App\Models\DefaultFine;


use App\Models\Reply;
use App\Models\TimeDuration;

use App\Models\Book; // Import your Book model
use App\Models\AcceptedRequest; // Import your Book model
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\UserBookRequest;

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


        $latestDefaultFine = DefaultFine::orderBy('updated_at', 'desc')->first();


        // Check if a default fine record exists
        if ($latestDefaultFine) {
            $acceptedRequest->default_fine_id = $latestDefaultFine->id;
        } else {
            // Handle the case where there is no default fine record (provide a default value or error handling)
            // You can set a default value or handle this case as needed.
        }



         // Retrieve the values from the form and format them as datetime values
        $acceptedRequest->date_pickup = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_pickup'));
        $acceptedRequest->date_return = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_return'));

            // Create a TimeDuration record
        $timeDuration = new TimeDuration();
        $timeDuration->date_pickup_seconds = $acceptedRequest->date_pickup->timestamp;
        $timeDuration->date_return_seconds = $acceptedRequest->date_return->timestamp;


        // Update the timestamp to the current time
        $acceptedRequest->updated_at = now();

        // Store the default fines in the session
        $request->session()->put('fines', $acceptedRequest->fines);


        $acceptedRequest->save();

            // Create a TimeDuration record
        $timeDuration = new TimeDuration();
        $timeDuration->accepted_request_id = $acceptedRequest->id;
        $timeDuration->date_pickup_seconds = $acceptedRequest->date_pickup->timestamp;
        $timeDuration->date_return_seconds = $acceptedRequest->date_return->timestamp;
        $timeDuration->save();

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

        foreach ($acceptedRequests as $acceptedRequest) {
            // Set total_fines directly from the AcceptedRequest model
            $acceptedRequest->total_fines = $acceptedRequest->total_fines ?? 0;
        }

            // Retrieve the default fine amount for each accepted request
        // foreach ($acceptedRequests as $acceptedRequest) {
        //     $defaultFine = DefaultFine::find($acceptedRequest->default_fine_id);

        //     // If a default fine record exists, set the 'defaultFineAmount' attribute
        //     if ($defaultFine) {
        //         $acceptedRequest->total_fines = $defaultFine->amount;
        //     } else {
        //         // Handle the case where there is no default fine record (provide a default value or error handling)
        //         // You can set a default value or handle this case as needed.
        //         $acceptedRequest->total_fines = 0;
        //     }
        // }


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

        // Retrieve the user's notifications from the database and sort them in ascending order by the created_at timestamp
        $userNotifications = UserNotification::where('user_id', $user->id)
            ->with('notification')
            ->orderBy('created_at', 'desc')
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


    public function returnBook($id)
    {
        // Find the AcceptedRequest record
        $transaction = AcceptedRequest::find($id);

        // Check if the record exists
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found');
        }

        $transaction->update([
            'book_returned' => true, // Set it as a boolean true
        ]);

        return redirect()->back()->with('success', 'Book returned successfully');
    }





    public function destroy($id){
        // Find the AcceptedRequest record
        $transaction = AcceptedRequest::find($id);

        // Check if the record exists
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found');
        }

        // Find the related TimeDuration record
        $timeDuration = TimeDuration::where('accepted_request_id', $id)->first();

        // Find the related UserBookRequest record
        $userBookRequest = UserBookRequest::where('user_id', $transaction->user_id)->first();

        // Decrement the request count by 1 or set to a specific value as needed
        if ($userBookRequest) {
            $userBookRequest->request_count--;
            $userBookRequest->save();
        }

        // Delete the TimeDuration record first
        if ($timeDuration) {
            $timeDuration->delete();
        }

        // Delete the AcceptedRequest record
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

        $defaultFine = DefaultFine::orderBy('updated_at', 'desc')->first();

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
            'defaultFine' => $defaultFine,
        ]);
    }





}
