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
        return redirect()->back()->with('success', 'Replied successfully');
    }

    public function destroy(Reply $reply)
    {
        // Check if the logged-in user is authorized to delete the reply
        if (auth()->user()->id === $reply->user_id) {
            $reply->delete();
            return redirect()->back()->with('success', 'Reply deleted');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to delete this reply.');
        }
    }
    public function update(Request $request, Reply $reply)
    {
        // Check if the logged-in user is authorized to update the reply
        if (auth()->user()->id === $reply->user_id) {
            // Validate the request data
            $request->validate([
                'reply' => 'required|string',
            ]);

            // Update the reply
            $reply->update([
                'reply' => $request->input('reply'),
            ]);

            return redirect()->back()->with('success', 'Reply updated');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to update this reply.');
        }
    }



}
