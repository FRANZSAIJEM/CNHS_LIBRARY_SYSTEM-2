<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\ArchiveBook;

class ArchiveBookController extends Controller
{
    public function archivebookIndex(Request $request)
    {
        $query = ArchiveBook::with('book');

        // Apply search filter
        if ($request->has('book_search')) {
            $bookSearch = $request->input('book_search');
            $query->whereHas('book', function ($subquery) use ($bookSearch) {
                $subquery->where('title', 'LIKE', '%' . $bookSearch . '%')
                    ->orWhere('author', 'LIKE', '%' . $bookSearch . '%');
            });
        }

    // Handle subject filter
    if ($request->has('subject_filter')) {
        $subjectFilter = $request->input('subject_filter');
        if ($subjectFilter) {
            $query->whereHas('book', function ($subquery) use ($subjectFilter) {
                $subquery->where('subject', $subjectFilter);
            });
        }
    }

        // Fetch all archived books based on the applied filters
        $archivedBooks = $query->get();

        return view('archiveBooks', compact('archivedBooks'));
    }


    public function archiveBook($id)
    {
        $book = Book::find($id);

        if (!$book) {
            // Handle the case where the book is not found.
            // You may want to redirect the user or show an error message.
        }

        // Create a record in the archive_books table
        ArchiveBook::create(['book_id' => $book->id]);

        // Optionally, you can delete the book from the original table if needed.
        // $book->delete();

        // Redirect the user or perform any other actions you need.
        return redirect()->route('archivebook')->with('success', 'Book archived successfully');
    }

    public function deleteArchivedBook($id)
    {
        $archivedBook = ArchiveBook::where('book_id', $id)->first();

        if (!$archivedBook) {
            // Handle the case where the archived book is not found.
            // You may want to redirect the user or show an error message.
            return redirect()->route('archivebook')->with('error', 'Archived book not found.');
        }

        // Delete the record from the archive_books table
        $archivedBook->delete();

        // Redirect the user or perform any other actions you need.
        return redirect()->route('archivebook')->with('success', 'Archived book deleted successfully');
    }


}
