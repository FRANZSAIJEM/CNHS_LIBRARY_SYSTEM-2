<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\book;
use App\Models\AcceptedRequest;
use Illuminate\Pagination\Paginator;



class BookListController extends Controller
{
    public function index(Request $request)
    {
        $query = book::query();

        if ($request->has('book_search')) {
            $bookSearch = $request->input('book_search');
            $query->where(function ($subquery) use ($bookSearch) {
                $subquery->where('title', 'LIKE', '%' . $bookSearch . '%')
                        ->orWhere('author', 'LIKE', '%' . $bookSearch . '%');
            });
        }


        // if ($request->has('request_search')) {
        //     $requestSearch = $request->input('request_search');
        //     $query->orWhere('name', 'LIKE', '%' . $requestSearch . '%')
        //           ->orWhere('id_number', 'LIKE', '%' . $requestSearch . '%');
        // }

        $bookLists = $query->paginate(4);

        return view('bookList', ['bookList' => $bookLists]);
    }

    public function destroy(Request $request, $id)
    {
        // Find the book by its ID
        $book = Book::findOrFail($id);

        // Delete the replies associated with the comments
        foreach ($book->comments as $comment) {
            $comment->replies()->delete(); // Delete associated replies
            $comment->likes()->delete(); // Delete likes associated with the comment
        }

        // Delete the comments associated with the book
        $book->comments()->delete(); // Delete associated comments

        // Delete the records in accepted_requests table that reference the book
        $book->acceptedRequests()->delete(); // Delete associated accepted requests

        // Get the users who have requested this book
        $users = $book->requestedByUsers;

        // Detach the book from all users who requested it
        foreach ($users as $user) {
            $user->requestedBooks()->detach($book->id); // Remove book from user's requested books
        }

        // Delete the book
        $book->delete(); // Delete the book itself

        // Redirect back to the book list page or any other page you prefer
        return redirect()->route('bookList')->with('success', 'Book and associated data deleted successfully');
    }




}
