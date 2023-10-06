<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\book;
use App\Models\AcceptedRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;




class StudentController extends Controller
{

    public function calculateTotalFines($userId)
    {
        // Calculate the total fines for a user based on their user ID
        return AcceptedRequest::where('user_id', $userId)->sum('fines');
    }



    public function index(Request $request)
    {
        $query = User::where('is_admin', false);
        $fines = $request->session()->get('fines');

        if ($request->has('id_number_search')) {
            $idNumberSearch = $request->input('id_number_search');
            $query->where(function ($subquery) use ($idNumberSearch) {
                $subquery->where('id_number', 'LIKE', '%' . $idNumberSearch . '%')
                    ->orWhere('name', 'LIKE', '%' . $idNumberSearch . '%');
            });
        }

        $students = $query->get();

        // Calculate total fines for each student
        foreach ($students as $student) {
            // Calculate the total fines for this student using the function
            $student->totalFines = $this->calculateTotalFines($student->id);
        }

        return view('student', ['students' => $students, 'fines' => $fines]);
    }


    public function disableAccount($id)
    {
        $student = User::findOrFail($id);
        $student->is_disabled = true;
        $student->save();

        return redirect()->route('student')->with('success', 'Account disabled successfully.');
    }

    public function toggleAccountStatus($id)
    {
        $student = User::findOrFail($id);
        $student->is_disabled = !$student->is_disabled;
        $student->save();

        $message = $student->is_disabled ? 'Account disabled.' : 'Account enabled.';
        return redirect()->route('student')->with('success', $message);
    }

    public function requestIndex(Request $request)
    {
        // Retrieve all users with related requested books
        $usersQuery = User::with('requestedBooks');

        // Check if the "book_search" input field is provided in the request
        if ($request->has('book_search')) {
            $searchTerm = $request->input('book_search');
            // Add a where condition to filter users by the "ID Number" field
            $usersQuery->where('id_number', 'LIKE', "%$searchTerm%");
        }

        // Get the filtered users
        $users = $usersQuery->get();

        // Calculate the total count of requests
        $totalRequests = $users->pluck('requestedBooks')->flatten()->count();

        return view('requests', compact('users', 'totalRequests'));
    }



    public function requestBook(Request $request, $id)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not logged in
        }

        // Get the logged-in user
        $user = Auth::user();

        // Get the book ID from the form input
        $bookId = $request->input('book_id');

        // Find the book by ID
        $book = book::findOrFail($bookId);

        // Store the request information in the database
        $user->requestedBooks()->attach($book);

        return redirect()->route('viewBook', ['id' => $bookId])->with('success', 'Requested successfully.');
    }



}
