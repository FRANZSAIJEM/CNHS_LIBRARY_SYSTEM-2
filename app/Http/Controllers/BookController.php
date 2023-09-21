<?php

namespace App\Http\Controllers;

use App\Models\book;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BookDecline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\RequestedBook;



class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('book');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'subject' => 'required|max:255',
            'availability' => 'required|in:Available,Not Available',
            'isbn' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('book_images', 'public');
            $validatedData['image'] = $imagePath;
        }



        Book::create($validatedData);

        return redirect()->route('bookList')->with('success', 'Book added successfully!');
    }

    public function edit($id)
    {
        // Retrieve the book by ID from the database
        $book = Book::findOrFail($id);

        // Return the view for editing the book with the book data
        return view('editBook', compact('book'));
    }

    public function update(Request $request, $id)
    {
        // Step 1: Validate the input data
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'subject' => 'required|max:255',
            'availability' => 'required|in:Available,Not Available',
            'isbn' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // 'sometimes' allows the image to be optional
            // Add validation rules for other fields as needed
        ]);

        // Step 2: Find the book by ID
        $book = Book::findOrFail($id);

        // Step 3: Update the book's attributes
        $book->update($validatedData);

        if ($request->hasFile('image')) {
            // Delete the previous image if it exists
            Storage::disk('public')->delete($book->image);

            // Store the new image
            $imagePath = $request->file('image')->store('book_images', 'public');
            $book->image = $imagePath;
        }


        // Step 4: Save the changes to the database
        $book->save();

        // Step 5: Redirect to a relevant page
        return redirect()->route('bookList')->with('success', 'Book updated successfully');
    }

    public function viewBook($id)
    {
        $book = Book::find($id);
        $userHasRequestedThisBook = false;
        $userHasAcceptedRequest = false; // Initialize the variable

        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has already requested this book
            $userHasRequestedThisBook = $user->hasRequestedBook($book->id);

            // Check if the user has accepted the request for this book (You should define this logic)
            $userHasAcceptedRequest = $user->hasAcceptedRequestForBook($book->id);
        }

        return view('viewBook', compact('book', 'userHasRequestedThisBook', 'userHasAcceptedRequest'));
    }


    public function removeRequest($userId, $bookId)
    {
        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Detach the request for the specific book and user
        $user->requestedBooks()->wherePivot('book_id', $bookId)->detach();

        return redirect()->back()->with('success', 'Request removed successfully.');
    }

}
