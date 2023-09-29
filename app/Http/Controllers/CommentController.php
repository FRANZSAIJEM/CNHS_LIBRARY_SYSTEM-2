<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
            'comment' => 'required|string',
        ]);

        // Create a new comment record in the database
        $comment = new Comment();
        $comment->book_id = $validatedData['book_id'];
        $comment->user_id = auth()->user()->id; // You may adjust this based on your authentication logic
        $comment->comment = $validatedData['comment'];
        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully!');
    }
    public function destroy(Comment $comment)
    {
        // Check if the logged-in user is authorized to delete the comment
        if (auth()->user()->id === $comment->user_id) {
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
    }

    public function edit(Comment $comment)
    {
        // Check if the logged-in user is authorized to edit the comment
        if (auth()->user()->id === $comment->user_id) {
            return view('comments.edit', compact('comment'));
        } else {
            return redirect()->back()->with('error', 'You are not authorized to edit this comment.');
        }
    }

}
