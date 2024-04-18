<?php

namespace App\Http\Controllers;

use App\Models\Displayed;
use App\Models\Post; // Ensure you have a Post model with the $fillable property
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostManagement extends Controller
{
    public function store(Request $request)
    {
        // Validate that not all fields are empty and user_id is not required here as it will be fetched from auth
        $request->validate([
            'content_text' => 'nullable|string',
            'element_type' => 'nullable|string',
            'element_path' => 'nullable|string',
            'user_id' => 'prohibited', // Ensuring user_id is not provided in the request
        ]);

        // Default values for missing fields
        $data = [
            'content_text' => $request->input('content_text', 'nothing'),
            'element_type' => $request->input('element_type', 'nothing'),
            'element_path' => $request->input('element_path', 'nothing'),
            'user_id' => Auth::id(), // Get the authenticated user's ID
        ];

        // Check if all fields are 'nothing', which is not allowed
        if ($data['content_text'] === 'nothing' && $data['element_type'] === 'nothing' && $data['element_path'] === 'nothing') {
            return response()->json(['error' => 'At least one field must be provided'], 422);
        }
        // dd("hhhh");
        // Crea te the post
        // dd($data['content_text']);
        $post = Post::create($data);
        if ($post) {
            // Create a new Displayed record
            $displayed = new Displayed([
                'post_id' => $post->id, // Use the ID of the newly created post
                'user_id' => Auth::id(), // Optionally, you can also store the user ID
                'partage_id' => 0,
            ]);

            // Save the Displayed record to the database
            $displayed->save();

            // Return success response including the Displayed info
            return response()->json([
                'message' => 'Post and display record created successfully',
                'post' => $post,
                'displayed' => $displayed
            ], 201);
        } else {
            // Handle the case where the post wasn't created successfully
            return response()->json(['error' => 'Failed to create the post'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $request->validate([
            'content_text' => 'nullable|string',
            'element_type' => 'nullable|string',
            'element_path' => 'nullable|string',
        ]);

        $post->update($request->only(['content_text', 'element_type', 'element_path']));

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 200);
    }


    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json(['post' => $post], 200);
    }
}
