<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class commentmanagement extends Controller
{
    //
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'text' => 'required|string',
            'user_id' => 'required|integer',
            'displayed_id' => 'required|integer'
        ]);

        $comment = new Comment($validatedData);
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
}
