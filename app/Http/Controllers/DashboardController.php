<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\book;
use App\Models\AcceptedRequest;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('is_admin', false)->count();
        $totalBooks = Book::count();

        // Calculate the total number of distinct user-book pairs
        $totalRequests = DB::table('book_requests')->count();
        $totalFines = $this->calculateTotalFines(Auth::id()); // Calculate total fines

        return view('dashboard')
            ->with('totalStudents', $totalStudents)
            ->with('totalBooks', $totalBooks)
            ->with('totalRequests', $totalRequests)
            ->with('totalFines', $totalFines);
    }

    private function calculateTotalFines($userId)
    {
        // Calculate the total fines for a user based on their user ID
        return AcceptedRequest::where('user_id', $userId)->sum('fines');
    }
}
