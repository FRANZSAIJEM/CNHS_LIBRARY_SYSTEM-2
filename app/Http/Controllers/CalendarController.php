<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserNotification;

class CalendarController extends Controller
{
    public function index()
    {
        return view('history');
    }

    public function events()
    {
        // Fetch UserNotification data from the database
        $userNotifications = UserNotification::all(); // Adjust this based on your actual model and conditions

        // Format data for FullCalendar
        $events = [];
        foreach ($userNotifications as $notification) {
            $events[] = [
                'title' => $notification->title,
                'start' => $notification->created_at->toDateString(), // Adjust this based on your date format
                // Add more properties as needed
            ];
        }

        return response()->json($events);
    }
}
