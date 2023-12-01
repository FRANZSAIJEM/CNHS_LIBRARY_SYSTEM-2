<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\book;


class PdfController extends Controller
{

    public function index()
    {
        // Retrieve all users who have accepted requests
        $usersWithAcceptedRequests = User::whereHas('acceptedRequests')->get();


        // Retrieve the count of users for each grade level
        $gradeLevelCounts = $usersWithAcceptedRequests->groupBy('grade_level')->map->count();

        // Retrieve the most borrowed books based on the 'count' field
        $mostBorrowedBooks = Book::orderBy('count', 'desc')->get();

         // Retrieve the count of available and not available books
        $availableBooksCount = book::where('availability', 'Available')->count();
        $notAvailableBooksCount = book::where('availability', 'Not Available')->count();
        $allBooksCount = book::count();


        $goodBooksCount = book::where('status', 'Good')->count();
        $damageBooksCount = book::where('status', 'Damage')->count();
        $lostBooksCount = book::where('status', 'Lost')->count();





         // Pass the data to the view
        return view('reports', [
            'usersWithAcceptedRequests' => $usersWithAcceptedRequests,
            'gradeLevelCounts' => $gradeLevelCounts,
            'availableBooksCount' => $availableBooksCount,
            'notAvailableBooksCount' => $notAvailableBooksCount,
            'allBooksCount' => $allBooksCount,
            'goodBooksCount' => $goodBooksCount,
            'damageBooksCount' => $damageBooksCount,
            'lostBooksCount' => $lostBooksCount,
            'mostBorrowedBooks' => $mostBorrowedBooks,
        ]);
    }



    public function generatePdf()
    {
        // Retrieve all users who have accepted requests
        $usersWithAcceptedRequests = User::whereHas('acceptedRequests')->get();

        // Retrieve the count of users for each grade level
        $gradeLevelCounts = $usersWithAcceptedRequests->groupBy('grade_level')->map->count();

        // Retrieve the most borrowed books based on the 'count' field
        $mostBorrowedBooks = Book::orderBy('count', 'desc')->get();

        // Retrieve the count of available and not available books
        $availableBooksCount = Book::where('availability', 'Available')->count();
        $notAvailableBooksCount = Book::where('availability', 'Not Available')->count();
        $allBooksCount = Book::count();

        $goodBooksCount = Book::where('status', 'Good')->count();
        $damageBooksCount = Book::where('status', 'Damage')->count();
        $lostBooksCount = Book::where('status', 'Lost')->count();

        // Define the data array
        $data = [
            'usersWithAcceptedRequests' => $usersWithAcceptedRequests,
            'gradeLevelCounts' => $gradeLevelCounts,
            'availableBooksCount' => $availableBooksCount,
            'notAvailableBooksCount' => $notAvailableBooksCount,
            'allBooksCount' => $allBooksCount,
            'goodBooksCount' => $goodBooksCount,
            'damageBooksCount' => $damageBooksCount,
            'lostBooksCount' => $lostBooksCount,
            'mostBorrowedBooks' => $mostBorrowedBooks,

        ];

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.sample', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);
    }
}
