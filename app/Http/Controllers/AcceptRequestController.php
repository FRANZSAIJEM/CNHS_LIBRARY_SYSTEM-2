<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;
use App\Models\DefaultFine;


use App\Models\Reply;
use App\Models\TimeDuration;

use Illuminate\Validation\Rule; // Import the Rule class

use App\Models\Book; // Import your Book model
use App\Models\AcceptedRequest; // Import your Book model
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\UserBookRequest;
use App\Models\returnedBook; // Import the ReturnedBook model
use App\Models\ReturnedBookNotification; // Import the ReturnedBook model

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class AcceptRequestController extends Controller
{
    public function acceptRequest(Request $request, User $user, Book $book)
    {

        // Set the timezone to your application's timezone
        Config::set('app.timezone', 'Asia/Manila');

        $acceptedRequest = new AcceptedRequest();
        $acceptedRequest->user_id = $user->id;
        $acceptedRequest->book_id = $book->id;
        $acceptedRequest->book_title = $book->title;
        $acceptedRequest->borrower_id = $user->id;
        $acceptedRequest->date_borrow = now();

        $latestDefaultFine = DefaultFine::orderBy('updated_at', 'desc')->first();

        if ($latestDefaultFine) {
            $acceptedRequest->default_fine_id = $latestDefaultFine->id;
        } else {

        }
        $acceptedRequest->date_pickup = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_pickup'));
        $acceptedRequest->date_return = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_return'));


        $timeDuration = new TimeDuration();
        $timeDuration->date_pickup_seconds = $acceptedRequest->date_pickup->timestamp;
        $timeDuration->date_return_seconds = $acceptedRequest->date_return->timestamp;

        $acceptedRequest->updated_at = now();
        $request->session()->put('fines', $acceptedRequest->fines);

        $acceptedRequest->save();

        $timeDuration = new TimeDuration();
        $timeDuration->accepted_request_id = $acceptedRequest->id;
        $timeDuration->date_pickup_seconds = $acceptedRequest->date_pickup->timestamp;
        $timeDuration->date_return_seconds = $acceptedRequest->date_return->timestamp;
        $timeDuration->save();

        // Detach the book from the user's requestedBooks relationship since it's been accepted.
        $user->requestedBooks()->detach($book);

        $notificationText = "{$user->name} Borrowed '{$book->title}' ";

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


        // Update the is_borrowed field for the user to true
        $user->is_borrowed = true;
        $user->save();

        $user->borrowed_count++;
        $user->save();

        $book->count++;
        $book->save();

        // Mark the book as borrowed (set is_borrowed to true)
        $book->update(['is_borrowed' => true]);

        // Redirect back to the previous page or wherever you want.
        return redirect()->back()->with('success', 'Accepted and saved.')
        ->with('notification', $notificationText);
    }











    public function transactions(Request $request)
    {
        $idNumberSearch = $request->input('id_number_search');
        $startDate = $request->input('start_date') ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() : null;
        // Retrieve all accepted requests from the database
        $query = AcceptedRequest::with('user', 'book');

        // If there is an ID number search query, filter by it
        if (!empty($idNumberSearch)) {
            $query->whereHas('user', function ($q) use ($idNumberSearch) {
                $q->where('id_number', 'LIKE', "%$idNumberSearch%")
                  ->orWhere('name', 'LIKE', "%$idNumberSearch%");
            })
            ->orWhereHas('book', function ($bookQuery) use ($idNumberSearch) {
                $bookQuery->where('title', 'LIKE', "%$idNumberSearch%");

            });
        }


         // Handle subject filter
        if ($request->has('subject_filter')) {
            $subjectFilter = $request->input('subject_filter');
            if ($subjectFilter) {
                // Assuming 'subject' is a column in the 'book' relationship
                $query->whereHas('book', function ($bookQuery) use ($subjectFilter) {
                    $bookQuery->where('subject', $subjectFilter);
                });
            }
        }


        if (!empty($startDate) && !empty($endDate)) {
            $query->where(function ($query) use ($startDate, $endDate) {
                $query->where('date_borrow', '>=', $startDate)
                      ->where('date_borrow', '<=', $endDate);
            });
        }

        $acceptedRequests = $query->get();

           // Load the users related to the accepted requests
        $acceptedRequests->load('user');

           // Load the books related to the accepted requests
        $acceptedRequests->load('book');


        // Retrieve distinct users and books directly from the database
        // $users = User::whereIn('id', $acceptedRequests->pluck('user_id'))->get();
        // $books = book::whereIn('id', $acceptedRequests->pluck('book_id'))->get();

        $users = User::all()->where('is_admin', false);
        $books = book::all();



        foreach ($acceptedRequests as $acceptedRequest) {
            // Set total_fines directly from the AcceptedRequest model
            $acceptedRequest->total_fines = $acceptedRequest->total_fines ?? 0;
        }

        $acceptedRequests->each(function ($acceptedRequest) {
            $acceptedRequest->date_borrow = \Carbon\Carbon::parse($acceptedRequest->date_borrow);
            $acceptedRequest->date_pickup = \Carbon\Carbon::parse($acceptedRequest->date_pickup);
            $acceptedRequest->date_return = \Carbon\Carbon::parse($acceptedRequest->date_return);
        });




        // Remove the search query parameter to clear the search
        $request->merge(['id_number_search' => null]);

        return view('transactions', compact('acceptedRequests', 'idNumberSearch', 'users', 'books'));
    }









    public function history(User $users, book $book)
    {
        // Get the currently authenticated user
        $user = Auth::user();


        $returnHistorys = ReturnedBookNotification::where('user_id', $user->id)
            ->with('notification')
            ->orderBy('created_at', 'desc')
            ->get();



        // Retrieve the user's notifications from the database and sort them in ascending order by the created_at timestamp
        $userNotifications = UserNotification::where('user_id', $user->id)
            ->with('notification')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history', compact('userNotifications', 'returnHistorys'));
    }






    public function clearNotification($id)
    {
        // Find the UserNotification record by ID
        $userNotification = UserNotification::find($id);


        // Check if the UserNotification record exists and delete it
        if ($userNotification) {
            $userNotification->delete();
        }

        // Redirect back to the history page or wherever you prefer
        return redirect()->back()->with('success', 'Cleared successfully');
    }

    public function clearReturnedNotification($id)
    {

        // Find the ReturnedBook record by ID
        $returnedBook = ReturnedBookNotification::find($id);

        // Check if the ReturnedBook record exists and delete it
        if ($returnedBook) {
            $returnedBook->delete();
        }

        // Redirect back to the history page or wherever you prefer
        return redirect()->back()->with('success', 'Cleared successfully');
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

        // Detach the book from the user's requestedBooks relationship since it's been accepted.
        $book = $transaction->book; // Assuming there's a 'book' relationship in your AcceptedRequest model
        $user = $transaction->user; // Assuming there's a 'user' relationship in your AcceptedRequest model

        $user->requestedBooks()->detach($book);

        // Mark the book as not borrowed (set is_borrowed to false)
        $book->update(['is_borrowed' => false]);

        $notificationText = "{$user->name} Returned '{$book->title}' ";

        $notification = new returnedBook([
            'borrower_id' => $user->id,
            'notification_text' => $notificationText,
            'created_at' => now(),
        ]);

        $notification->save();

        $userIdsToNotify = User::pluck('id')->toArray();

        $usersToNotify = User::whereIn('id', $userIdsToNotify)->get();

        foreach ($usersToNotify as $userToNotify) {
            $userNotification = new ReturnedBookNotification([
                'user_id' => $userToNotify->id,
                'returnedBook_id' => $notification->id,
            ]);
            $userNotification->save();
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








    public function duplicateAcceptRequest(Request $request, User $user, Book $book)
    {
        // Set the timezone to your application's timezone
        Config::set('app.timezone', 'Asia/Manila');

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'date_pickup' => 'required|date_format:Y-m-d\TH:i',
            'date_return' => 'required|date_format:Y-m-d\TH:i',
        ]);

            // Retrieve the selected user and book
        $user = User::find($request->input('user_id'));
        $book = book::find($request->input('book_id'));


        $acceptedRequest = new AcceptedRequest();
        $acceptedRequest->user_id = $user->id;
        $acceptedRequest->book_id = $book->id;
        $acceptedRequest->book_title = $book->title;
        $acceptedRequest->borrower_id = $user->id;
        $acceptedRequest->date_borrow = now();

        $latestDefaultFine = DefaultFine::orderBy('updated_at', 'desc')->first();

        if ($latestDefaultFine) {
            $acceptedRequest->default_fine_id = $latestDefaultFine->id;
        } else {
            // Handle the case when there is no latest default fine
        }
        $acceptedRequest->date_pickup = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_pickup'));
        $acceptedRequest->date_return = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->input('date_return'));

        $timeDuration = new TimeDuration();
        $timeDuration->date_pickup_seconds = $acceptedRequest->date_pickup->timestamp;
        $timeDuration->date_return_seconds = $acceptedRequest->date_return->timestamp;

        $acceptedRequest->updated_at = now();
        $request->session()->put('fines', $acceptedRequest->fines);

        $acceptedRequest->save();

        $timeDuration = new TimeDuration();
        $timeDuration->accepted_request_id = $acceptedRequest->id;
        $timeDuration->date_pickup_seconds = $acceptedRequest->date_pickup->timestamp;
        $timeDuration->date_return_seconds = $acceptedRequest->date_return->timestamp;
        $timeDuration->save();

        // Detach the book from the user's requestedBooks relationship since it's been accepted.
        $user->requestedBooks()->detach($book);

        $notificationText = "{$user->name} Borrowed '{$book->title}' ";

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

        // Update the is_borrowed field for the user to true
        $user->is_borrowed = true;
        $user->save();

        $user->borrowed_count++;
        $user->save();

        $book->count++;
        $book->save();

       // Find or create the user's book request record
       $userBookRequest = UserBookRequest::firstOrNew(['user_id' => $user->id]);
       $userBookRequest->request_count++;
       $userBookRequest->save();

       // Mark the book as borrowed (set is_borrowed to true)
        $book->update(['is_borrowed' => true]);

        // Redirect back to the previous page or wherever you want.
        return redirect()->back()->with('success', 'Accepted and saved.')
            ->with('notification', $notificationText);
    }




}
