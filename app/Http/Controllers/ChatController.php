<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;




class ChatController extends Controller
{
    public function startChat($userId)
    {
        $user = User::find($userId);

        return view('chat', compact('user'));
    }



    public function startChatStud()
    {

        $user = Auth::user();

        return view('chat', compact('user'));
    }



    public function sendMessage(Request $request)
    {
        // Validate input
        $request->validate([
            'message_content' => 'required',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $message = new Chat;
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->input('receiver_id');
        $message->message_content = $request->input('message_content');
        $message->save();

        // Return to the same chat page with the user's data
        $user = User::find($request->input('receiver_id'));

        return view('chat', compact('user'));
    }



}
