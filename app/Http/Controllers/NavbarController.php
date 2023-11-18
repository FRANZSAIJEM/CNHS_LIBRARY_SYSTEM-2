<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcceptedRequest;
use Illuminate\Support\Facades\Auth;


class NavbarController extends Controller
{

    public function index(){
        $acceptedRequests = AcceptedRequest::where('user_id', Auth::id())->get();
        // Check if there are any accepted requests for the user
        $date_pickup = $date_return = null;

        if ($acceptedRequests->count() > 0) {
            // For simplicity, assuming the first accepted request's dates are used
            $date_pickup = $acceptedRequests[0]->date_pickup;
            $date_return = $acceptedRequests[0]->date_return;
        }

        return view('navbar')
            ->with('date_pickup', $date_pickup)
            ->with('date_return', $date_return)
            ->with('acceptedRequests', $acceptedRequests); // Pass $acceptedRequests to the view
    }
}
