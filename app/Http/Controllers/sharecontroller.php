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

        $validated = $request->validate([
            'text' => 'required|string',
            'post_id' => 'required|integer|exists:posts,id',
        ]);


        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        try {

            $sharedPost = new post_partaged([
                'text' => $validated['text'],
                'post_id' => $validated['post_id'],
                'user_id' => Auth::id(),
            ]);


            $sharedPost->save();


            $displayData = new Displayed([
                'post_id' => $sharedPost->post_id,
                'partage_id' => $sharedPost->id,
                'user_id' => Auth::id(),
            ]);


            $displayData->save();

            
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
