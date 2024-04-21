<?php

namespace App\Http\Controllers;

use App\Models\Displayed;
use Illuminate\Http\Request;
use App\Models\post_partaged; // Ensure you use the correct namespace
use Illuminate\Support\Facades\Auth; // For authentication

class ShareController extends Controller
{
    /**
     * Store a shared post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeasharepost(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'text' => 'required|string',
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        try {
            // Create a new shared post
            $sharedPost = new post_partaged([
                'text' => $validated['text'],
                'post_id' => $validated['post_id'],
                'user_id' => Auth::id(),
            ]);

            // Save the shared post
            $sharedPost->save();

            // Assuming Displayed model exists and is correctly set up
            $displayData = new Displayed([
                'post_id' => $sharedPost->post_id,
                'partage_id' => $sharedPost->id,
                'user_id' => Auth::id(),
            ]);

            // Save display data
            $displayData->save();

            // Return a response with all the relevant data
            return response()->json([
                'message' => 'Post shared successfully',
                'data' => $sharedPost,
                'displayData' => $displayData
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to share post: ' . $e->getMessage()], 500);
        }
    }
}
