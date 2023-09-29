<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;

class RepliesController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'reply' => 'required|string',
        ]);

        // Create a new reply
        Reply::create([
            'comment_id' => $request->input('comment_id'),
            'user_id' => auth()->id(), // Assuming you are using authentication
            'reply' => $request->input('reply'),
        ]);

        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Reply added successfully');
    }
}
