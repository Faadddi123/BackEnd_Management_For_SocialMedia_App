<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message; // Assuming you have a Message model

class messagecontroller extends Controller
{
    public function sendMessage(Request $request)
    {

        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Validate the request data
        $validated = $request->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string',
        ]);
        // return response()->json(['error' => 'User not authenticated'], 200);
        // Create and save the message to the database
        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $validated['receiver_id'];
        $message->text = $validated['message'];
        $message->save();

        // Return a success response
        return response()->json(['success' => 'Message sent successfully']);
    }



    public function loadMessages(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Validate the request data
        $validated = $request->validate([
            'other_user_id' => 'required|integer',
        ]);

        $currentUserId = Auth::id();
        $otherUserId = $validated['other_user_id'];

        // Query the database for messages between the two users
        $messages = Message::where(function ($query) use ($currentUserId, $otherUserId) {
            $query->where('sender_id', $currentUserId)
                ->where('receiver_id', $otherUserId);
        })->orWhere(function ($query) use ($currentUserId, $otherUserId) {
            $query->where('sender_id', $otherUserId)
                ->where('receiver_id', $currentUserId);
        })->orderBy('id', 'desc') // Assuming newer messages have higher IDs
        ->take(10)
        ->get();

        // Return the messages
        return response()->json($messages);
    }


    public function deleteMessage($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $message = Message::find($id);

        if ($message && $message->sender_id == Auth::id()) {
            $message->delete();
            return response()->json(['success' => 'Message deleted successfully']);
        }

        return response()->json(['error' => 'Message not found or permission denied'], 404);
    }

    public function updateMessage(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::find($id);

        if ($message && $message->sender_id == Auth::id()) {
            $message->text = $validated['message'];
            $message->save();
            return response()->json(['success' => 'Message updated successfully']);
        }

        return response()->json(['error' => 'Message not found or permission denied'], 404);
    }
}
