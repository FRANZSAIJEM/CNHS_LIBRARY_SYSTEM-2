<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\book;
use App\Models\UserNotification;
use App\Models\Notification;



use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{

    public function index()
    {
       // Retrieve users with non-zero borrowed_count
        $usersWithBorrowedCount = User::where('borrowed_count', '>', 0)
        ->get(['id', 'id_number', 'grade_level', 'name', 'borrowed_count']);



        // Retrieve the count of users for each grade level
        $gradeLevelCounts = $usersWithBorrowedCount->groupBy('grade_level')->map->count();



        // Retrieve the most borrowed books based on the 'count' field
        $mostBorrowedBooks = Book::orderBy('count', 'desc')->get();

         // Retrieve the count of available and not available books
        $availableBooksCount = book::where('availability', 'Available')->count();
        $notAvailableBooksCount = book::where('availability', 'Not Available')->count();
        $allBooksCount = book::count();


        $goodBooksCount = book::where('status', 'Good')->count();
        $damageBooksCount = book::where('status', 'Damage')->count();
        $lostBooksCount = book::where('status', 'Lost')->count();

        // Retrieve all notifications
        $notifications = Notification::all();

        // Group notifications by year and month
        $groupedNotifications = $notifications->groupBy(function ($date) {
            return $date->created_at->format('Y-m-d');
        });


         // Pass the data to the view
        return view('reports', [
            'usersWithBorrowedCount' => $usersWithBorrowedCount,
            'gradeLevelCounts' => $gradeLevelCounts,
            'availableBooksCount' => $availableBooksCount,
            'notAvailableBooksCount' => $notAvailableBooksCount,
            'allBooksCount' => $allBooksCount,
            'goodBooksCount' => $goodBooksCount,
            'damageBooksCount' => $damageBooksCount,
            'lostBooksCount' => $lostBooksCount,
            'mostBorrowedBooks' => $mostBorrowedBooks,
            'groupedNotifications' => $groupedNotifications,

        ]);
    }



    public function generatePdf()
    {
         // Retrieve users with non-zero borrowed_count
         $usersWithBorrowedCount = User::where('borrowed_count', '>', 0)
         ->get(['id', 'id_number', 'grade_level', 'name', 'borrowed_count']);

         // Retrieve the count of users for each grade level
         $gradeLevelCounts = $usersWithBorrowedCount->groupBy('grade_level')->map->count();

        // Retrieve the most borrowed books based on the 'count' field
        $mostBorrowedBooks = Book::orderBy('count', 'desc')->get();

        // Retrieve the count of available and not available books
        $availableBooksCount = Book::where('availability', 'Available')->count();
        $notAvailableBooksCount = Book::where('availability', 'Not Available')->count();
        $allBooksCount = Book::count();

        $goodBooksCount = Book::where('status', 'Good')->count();
        $damageBooksCount = Book::where('status', 'Damage')->count();
        $lostBooksCount = Book::where('status', 'Lost')->count();


         // Retrieve all notifications
        $notifications = Notification::all();

        // Group notifications by year and month
        $groupedNotifications = $notifications->groupBy(function ($date) {
            return $date->created_at->format('Y-m-d');
        });



        // Define the data array
        $data = [
            'usersWithBorrowedCount' => $usersWithBorrowedCount,
            'gradeLevelCounts' => $gradeLevelCounts,
            'availableBooksCount' => $availableBooksCount,
            'notAvailableBooksCount' => $notAvailableBooksCount,
            'allBooksCount' => $allBooksCount,
            'goodBooksCount' => $goodBooksCount,
            'damageBooksCount' => $damageBooksCount,
            'lostBooksCount' => $lostBooksCount,
            'mostBorrowedBooks' => $mostBorrowedBooks,
            'groupedNotifications' => $groupedNotifications,


        ];

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.sample', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);
    }
}
