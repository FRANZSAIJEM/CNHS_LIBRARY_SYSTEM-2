<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatMessage;


class ChatController extends Controller
{



    public function startChat($studentId)
    {
        // Retrieve the student based on the provided studentId
        $student = User::find($studentId);
        $chatMessages = ChatMessage::where('student_id', $studentId)->get();

        if (!$student) {
            // Handle the case where the student is not found
            return redirect()->back()->with('error', 'Student not found');
        }

        // Here, you can implement the logic to start a chat with the student

        return view('chat', compact('student', 'chatMessages'));
    }

    public function sendChatMessage($studentId)
    {
        $data = request()->validate([
            'message' => 'required',
        ]);

        $chatMessage = new ChatMessage();
        $chatMessage->message = $data['message'];
        $chatMessage->student_id = $studentId; // Set the student's ID

        $chatMessage->save();

        return back()->with('success', 'Message sent successfully');
    }
}
