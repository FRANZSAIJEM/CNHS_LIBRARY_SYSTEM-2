<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\book;
use App\Models\DefaultFine;

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
        $totalRequests = DB::table('book_requests')->count();
        $totalFines = $this->calculateTotalFines(Auth::id());

        // Fetch the accepted request for the authenticated user (assuming there is one)
        $acceptedRequest = AcceptedRequest::where('user_id', Auth::id())->first();

        // Check if there is an accepted request for the user
        $date_pickup = $date_return = null;

        if ($acceptedRequest) {
            $date_pickup = $acceptedRequest->date_pickup;
            $date_return = $acceptedRequest->date_return;
        }

        $defaultFine = DefaultFine::first();


        $totalFine = $acceptedRequest ? $acceptedRequest->total_fines : null;

        return view('dashboard')
            ->with('totalStudents', $totalStudents)
            ->with('totalBooks', $totalBooks)
            ->with('totalRequests', $totalRequests)
            ->with('totalFines', $totalFines)
            ->with('date_pickup', $date_pickup)
            ->with('date_return', $date_return)
            ->with('acceptedRequest', $acceptedRequest)
            ->with('defaultFine', $defaultFine)
            ->with('totalFine', $totalFine);
    }

    private function calculateTotalFines($userId)
    {
        return AcceptedRequest::where('user_id', $userId)->sum('fines');
    }
}
