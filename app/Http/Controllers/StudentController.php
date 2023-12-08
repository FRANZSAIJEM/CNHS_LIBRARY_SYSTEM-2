<?php

namespace App\Http\Controllers;

use App\Models\UserBookRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\book;
use App\Models\AcceptedRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;




class StudentController extends Controller
{

    public function calculateTotalFines($userId)
    {
        // Calculate the total fines for a user based on their user ID
        return AcceptedRequest::where('user_id', $userId)->sum('total_fines');
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

        $acceptedRequest = AcceptedRequest::where('user_id', Auth::id())->first();
        $date_pickup = $date_return = null;
        if ($acceptedRequest) {
            $date_pickup = $acceptedRequest->date_pickup;
            $date_return = $acceptedRequest->date_return;
        }

        // Join the 'chats' table to get users with existing chats
        $students = $query->leftJoin('chats', function ($join) {
            $join->on('users.id', '=', 'chats.sender_id')
                ->orWhere('users.id', '=', 'chats.receiver_id');
        })
        ->select(
            'users.id',
            'users.id_number',
            'users.name',
            'users.email',
            'users.contact',
            'users.gender',
            'users.grade_level',

            // Add other columns from the 'users' table here

            DB::raw('COUNT(chats.id) as chat_count'),
            DB::raw('MAX(chats.created_at) as latest_chat_date')
        )
        ->groupBy('users.id', 'users.id_number', 'users.name'
        , 'users.email'
        , 'users.contact'
        , 'users.gender'
        , 'users.grade_level') // Add other columns from the 'users' table here

        ->orderByDesc('latest_chat_date') // Order by the latest chat date in descending order
        ->get();

        // Calculate total fines for each student
        foreach ($students as $student) {
            // Calculate the total fines for this student using the function
            $student->totalFines = $this->calculateTotalFines($student->id);
        }

        return view('student', ['students' => $students, 'fines' => $fines])
            ->with('date_pickup', $date_pickup)
            ->with('date_return', $date_return)
            ->with('acceptedRequest', $acceptedRequest);
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



        // Check if start_date and end_date are provided in the request
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date') . ' 00:00:00';
            $endDate = $request->input('end_date') . ' 23:59:59';

            // Add a where condition to filter users by the created_at field within the date range
            $usersQuery->whereHas('requestedBooks', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('book_requests.created_at', [$startDate, $endDate]);
            });
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

        // Find or create the user's book request record
        $userBookRequest = UserBookRequest::firstOrNew(['user_id' => $user->id]);
        $userBookRequest->request_count++;
        $userBookRequest->save();

        // Store the request information in the database
        $user->requestedBooks()->attach($book);

        return redirect()->route('viewBook', ['id' => $bookId])->with('success', 'Requested successfully.');
    }



}
