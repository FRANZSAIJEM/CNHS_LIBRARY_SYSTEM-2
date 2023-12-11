<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcceptedRequest;
use App\Models\User;
use App\Models\book;




class ManualRecordController extends Controller
{

    public function index(Request $request){
        $query = $request->input('search');
        $users = User::where('is_admin', false)
                      ->where(function($q) use ($query) {
                          $q->where('name', 'LIKE', "%$query%")
                            ->orWhere('email', 'LIKE', "%$query%");
                      })
                      ->get();

        $books = Book::where('title', 'LIKE', "%$query%")->get();

        return view('manualRecord', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $idNumberSearch = $request->input('id_number_search');
        $startDate = $request->input('start_date') ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() : null;
        // Retrieve all accepted requests from the database
        $query = AcceptedRequest::with('user', 'book');

        // If there is an ID number search query, filter by it
        if (!empty($idNumberSearch)) {
            $query->whereHas('user', function ($q) use ($idNumberSearch) {
                $q->where('id_number', 'LIKE', "%$idNumberSearch%")
                  ->orWhere('name', 'LIKE', "%$idNumberSearch%");
            })
            ->orWhereHas('book', function ($bookQuery) use ($idNumberSearch) {
                $bookQuery->where('title', 'LIKE', "%$idNumberSearch%");
            });
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->where(function ($query) use ($startDate, $endDate) {
                $query->where('date_borrow', '>=', $startDate)
                      ->where('date_borrow', '<=', $endDate);
            });
        }

        $acceptedRequests = $query->get();

           // Load the users related to the accepted requests
        $acceptedRequests->load('user');

           // Load the books related to the accepted requests
        $acceptedRequests->load('book');


        // Retrieve distinct users and books directly from the database
        // $users = User::whereIn('id', $acceptedRequests->pluck('user_id'))->get();
        // $books = book::whereIn('id', $acceptedRequests->pluck('book_id'))->get();

        $users = User::all()->where('is_admin', false);
        $books = book::all();



        foreach ($acceptedRequests as $acceptedRequest) {
            // Set total_fines directly from the AcceptedRequest model
            $acceptedRequest->total_fines = $acceptedRequest->total_fines ?? 0;
        }

        $acceptedRequests->each(function ($acceptedRequest) {
            $acceptedRequest->date_borrow = \Carbon\Carbon::parse($acceptedRequest->date_borrow);
            $acceptedRequest->date_pickup = \Carbon\Carbon::parse($acceptedRequest->date_pickup);
            $acceptedRequest->date_return = \Carbon\Carbon::parse($acceptedRequest->date_return);
        });




        // Remove the search query parameter to clear the search
        $request->merge(['id_number_search' => null]);

        return view('transactions', compact('acceptedRequests', 'idNumberSearch', 'users', 'books'));
    }


}
