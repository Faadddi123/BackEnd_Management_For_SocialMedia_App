<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class commentmanagement extends Controller
{
    //
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'text' => 'required|string',
            'displayed_id' => 'required|integer'
        ]);

        $comment = new Comment($validatedData);
        $comment->user_id = Auth::id();; 
        $comment->save();

        return response()->json(['message' => 'Comment created successfully', 'data' => $comment], 201);
    }
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $validatedData = $request->validate([
            'text' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|integer',
            'displayed_id' => 'sometimes|required|integer'
        ]);

        $comment->update($validatedData);

        return response()->json(['message' => 'Comment updated successfully', 'data' => $comment]);
    }


    public function delete($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'] , 201);
    }


    public function getCommentsByDisplayedId($displayed_id)
    {
        $comments = Comment::with('user') // 'user' should be the name of the relationship method defined in the Comment model
                        ->where('displayed_id', $displayed_id)
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();

        if ($comments->isEmpty()) {
            return response()->json(['message' => 'No comments found for the provided displayed_id'], 404);
        }

        return response()->json(['message' => 'Comments retrieved successfully', 'data' => $comments]);
    }
}
