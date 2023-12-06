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



            // Retrieve all books
        $books = book::all();

        // Count occurrences of each subject
        $subjectCounts = $books->groupBy('subject')->map->count();

          // Get the count of available books for each subject
        $availableSubjectCounts = $books->where('availability', 'Available')->groupBy('subject')->map->count();


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
            'subjectCounts' => $subjectCounts,
            'availableSubjectCounts' => $availableSubjectCounts,
        ]);
    }


    public function generatePdfForStudents(){
         // Retrieve users with non-zero borrowed_count
         $usersWithBorrowedCount = User::where('borrowed_count', '>', 0)
         ->get(['id', 'id_number', 'grade_level', 'name', 'borrowed_count']);

        // Define the data array
        $data = [
            'usersWithBorrowedCount' => $usersWithBorrowedCount,

        ];

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.pdfForStudents', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);
    }



    public function generatePdfForLevelMostBorrowed(){
          // Retrieve users with non-zero borrowed_count
          $usersWithBorrowedCount = User::where('borrowed_count', '>', 0)
          ->get(['id', 'id_number', 'grade_level', 'name', 'borrowed_count']);

          // Retrieve the count of users for each grade level
          $gradeLevelCounts = $usersWithBorrowedCount->groupBy('grade_level')->map->count();

            // Define the data array
            $data = [
                'usersWithBorrowedCount' => $usersWithBorrowedCount,
                'gradeLevelCounts' => $gradeLevelCounts,
            ];

                    // Load the PDF view with the data
            $pdf = PDF::loadView('pdf.pdfForMostGradeLevelBorrowedCount', $data);

            // Use stream instead of download
            return $pdf->stream('sample.pdf', ['Attachment' => false]);


    }

    public function generatePdfForBookCondition(){
        // Retrieve the count of available and not available books
        $availableBooksCount = Book::where('availability', 'Available')->count();
        $notAvailableBooksCount = Book::where('availability', 'Not Available')->count();
        $allBooksCount = Book::count();

        $goodBooksCount = Book::where('status', 'Good')->count();
        $damageBooksCount = Book::where('status', 'Damage')->count();
        $lostBooksCount = Book::where('status', 'Lost')->count();


          // Define the data array
          $data = [
            'availableBooksCount' => $availableBooksCount,
            'notAvailableBooksCount' => $notAvailableBooksCount,
            'allBooksCount' => $allBooksCount,
            'goodBooksCount' => $goodBooksCount,
            'damageBooksCount' => $damageBooksCount,
            'lostBooksCount' => $lostBooksCount,


        ];

                // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.pdfForBookCondition', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);

    }



    public function generatePdfFormostBorrowedBooks(){
        // Retrieve the most borrowed books based on the 'count' field
        $mostBorrowedBooks = Book::orderBy('count', 'desc')->get();


         // Define the data array
         $data = [
            'mostBorrowedBooks' => $mostBorrowedBooks,

        ];

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.pdfForMostBorrowedBook', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);
    }


    public function generatePdfForborrowedByDate(){
       // Retrieve all notifications
       $notifications = Notification::all();

       // Group notifications by year and month
       $groupedNotifications = $notifications->groupBy(function ($date) {
           return $date->created_at->format('Y-m-d');
       });



        // Define the data array
        $data = [
            'groupedNotifications' => $groupedNotifications,
        ];

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.pdfForBorrowedByDate', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);

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


        // Retrieve all books
        $books = book::all();

        // Count occurrences of each subject
        $subjectCounts = $books->groupBy('subject')->map->count();

        // Get the count of available books for each subject
        $availableSubjectCounts = $books->where('availability', 'Available')->groupBy('subject')->map->count();


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
            'subjectCounts' => $subjectCounts,
            'availableSubjectCounts' => $availableSubjectCounts,

        ];

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.sample', $data);

        // Use stream instead of download
        return $pdf->stream('sample.pdf', ['Attachment' => false]);
    }


        // New function to generate PDF based on selected checkboxes
    public function generateSelectedPdf(Request $request)
    {
        // Get selected reports from the form
        $selectedReports = $request->input('reports');

        // Define an array to store data for selected reports
        $data = [];




        // Check if 'allStudents' is selected
        if (in_array('allStudents', $selectedReports)) {
            $data['usersWithBorrowedCount'] = User::where('borrowed_count', '>', 0)
                ->get(['id', 'id_number', 'grade_level', 'name', 'borrowed_count']);
        }

        // Check if 'gradeLevelMostBorrowed' is selected
        if (in_array('gradeLevelMostBorrowed', $selectedReports)) {
            $usersWithBorrowedCount = User::where('borrowed_count', '>', 0)
            ->get(['id', 'id_number', 'grade_level', 'name', 'borrowed_count']);
            $data['gradeLevelCounts'] = $usersWithBorrowedCount->groupBy('grade_level')->map->count();

        }


        // Check if 'mostBorrowedBooks' is selected
        if (in_array('mostBorrowedBooks', $selectedReports)) {
            $data['mostBorrowedBooks'] = Book::orderBy('count', 'desc')->get();
        }



          // Retrieve all books
        $books = Book::all();

        // Count occurrences of each subject
        $subjectCounts = $books->groupBy('subject')->map->count();

        // Get the count of available books for each subject
        $availableSubjectCounts = $books->where('availability', 'Available')->groupBy('subject')->map->count();

        // Check if 'bookSubjectCounts' is selected
        if (in_array('bookSubjectCounts', $selectedReports)) {
            $data['bookSubjectCounts'] = $subjectCounts;
            $data['availableSubjectCounts'] = $availableSubjectCounts;
        }




      // Check if 'bookCondition' is selected
        if (in_array('bookCondition', $selectedReports)) {
            $data['bookCondition'] = [
                'availableBooksCount' => Book::where('availability', 'Available')->count(),
                'notAvailableBooksCount' => Book::where('availability', 'Not Available')->count(),
                'allBooksCount' => Book::count(),
                'goodBooksCount' => Book::where('status', 'Good')->count(),
                'damageBooksCount' => Book::where('status', 'Damage')->count(),
                'lostBooksCount' => Book::where('status', 'Lost')->count(),
            ];
        }



       // Retrieve all books
       $books = book::all();

       // Count occurrences of each subject
       $subjectCounts = $books->groupBy('subject')->map->count();

       // Get the count of available books for each subject
       $availableSubjectCounts = $books->where('availability', 'Available')->groupBy('subject')->map->count();



        // Check if 'borrowedByDate' is selected
        if (in_array('borrowedByDate', $selectedReports)) {
            $notifications = Notification::all();

            // Group notifications by year and month
            $data['groupedNotifications'] = $notifications->groupBy(function ($date) {
                return $date->created_at->format('Y-m-d');
            });
        }

        // Load the PDF view with the data
        $pdf = PDF::loadView('pdf.pdfForSelectedReports', $data);

        // Use stream instead of download
        return $pdf->stream('selected_sample.pdf', ['Attachment' => false]);
    }
}
