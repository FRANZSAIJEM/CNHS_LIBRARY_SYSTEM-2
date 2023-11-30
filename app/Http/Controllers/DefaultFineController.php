<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DefaultFine;

class DefaultFineController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form input
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0', // You can add more validation rules if needed
        ]);

        // Find the existing default fine record or create a new one if it doesn't exist
        $defaultFine = DefaultFine::firstOrNew([]);

        // Update the amount
        $defaultFine->amount = $validatedData['amount'];
        $defaultFine->description = 'Default Fine Description'; // You can customize this description

        // Save the record
        $defaultFine->save();

        // Redirect back with a success message or do something else as needed
        return redirect()->back()->with('success', 'Set successfully!');
    }





    public function storeDaily(Request $request){
        $validatedData = $request->validate([
            'set_daily_fines' => 'required|numeric|min:0', // You can add more validation rules if needed
        ]);

        $totalAmount = DefaultFine::firstOrNew([]);
        $totalAmount->set_daily_fines = $validatedData['set_daily_fines'];

        // Save the record
        $totalAmount->save();
        return redirect()->back()->with('success', 'Set successfully!');
    }
}
