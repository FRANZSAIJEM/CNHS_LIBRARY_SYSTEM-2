<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcceptedRequest;
use Illuminate\Support\Facades\Auth;


class NavbarController extends Controller
{
    //

    public function index(){
        $acceptedRequest = AcceptedRequest::where('user_id', Auth::id())->first();
        // Check if there is an accepted request for the user
        $date_pickup = $date_return = null;

        if ($acceptedRequest) {
            $date_pickup = $acceptedRequest->date_pickup;
            $date_return = $acceptedRequest->date_return;
        }


        return view('navbar')
            ->with('date_pickup', $date_pickup)
            ->with('date_return', $date_return)
            ->with('acceptedRequest', $acceptedRequest); // Pass $acceptedRequest to the view
    }
}
