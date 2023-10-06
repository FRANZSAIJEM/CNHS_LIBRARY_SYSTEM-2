<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike;

class CommentController extends Controller
{
    public function countReplies($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $replyCount = $comment->replies->count();

        return $replyCount;
    }

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

        return redirect()->back()->with('success', 'Added successfully!');
    }
    public function destroy(Comment $comment)
    {
        // Check if the logged-in user is authorized to delete the comment
        if (auth()->user()->id === $comment->user_id) {
            $comment->replies()->delete();
            $comment->likes()->delete();

            $comment->delete();
            return redirect()->back()->with('success', 'Deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
    }
    public function update(Request $request, Comment $comment)
    {
        // Check if the logged-in user is authorized to update the reply
        if (auth()->user()->id === $comment->user_id) {
            // Validate the request data
            $request->validate([
                'comment' => 'required|string',
            ]);

            // Update the comment
            $comment->update([
                'comment' => $request->input('comment'),
            ]);

            return redirect()->back()->with('success', 'Comment updated successfully');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to update this comment.');
        }
    }

    public function like(Comment $comment)
    {
        $user = auth()->user();

        if (!$user->hasLikedComment($comment)) {
            // Create a new CommentLike record.
            $like = new CommentLike();
            $like->user_id = $user->id;
            $like->comment_id = $comment->id;
            $like->save();

            return response()->json(['success' => true, 'liked' => true, 'likes_count' => $comment->likes()->count()]);
        } else {
            dd('Reached the else block');
            // Remove the existing like
            $user->likes()->where('comment_id', $comment->id)->delete();

            return response()->json(['success' => true, 'liked' => false, 'likes_count' => $comment->likes()->count()]);
        }
    }



}
