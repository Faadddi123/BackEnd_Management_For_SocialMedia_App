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
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240' // Validation for media files
        ]);
        // return response()->json([
        //     'message' => 'Post and display record created successfully',
        //     'post' => $request
        // ], 201);
        if($request->file('media')){
            $file = $request->file('media');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = $file->hashName();



            if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                $path = $file->storeAs('images', $fileName, 'public');
                $elementType = 1;
            } elseif ($fileExtension === 'mp4') {
                $path = $file->storeAs('videos', $fileName, 'public');
                $elementType = 2;
            } else {
                return response()->json(['error' => 'Unsupported file type'], 415);
            }
        }else{
            $fileName = 'nothing';
            $elementType = 'nothing';
        }


        $data = [
        'content_text' => $request->input('content_text', 'nothing'),
        'element_type' => $elementType,
        'element_path' => $fileName,
        'user_id' => Auth::id(),
        ];


        if ($data['content_text'] === 'nothing' && $data['element_type'] === 'nothing' && $data['element_path'] === 'nothing') {
            return response()->json(['error' => 'At least one field must be provided'], 422);
        }
        // dd("hhhh");
        // Crea te the post
        // dd($data['content_text']);
        $post = Post::create($data);
        if ($post) {

            $displayed = new Displayed([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'partage_id' => 0,
            ]);


            $displayed->save();


            return response()->json([
                'message' => 'Post and display record created successfully',
                'post' => $post,
                'displayed' => $displayed
            ], 201);
        } else {

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

    public function getpostinfo($id)
    {

        $post = Post::find($id);


        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }


        return response()->json([
            'post' => [
                'id' => $post->id,
                'content_text' => $post->content_text,
                'element_type' => $post->element_type,
                'element_path' => $post->element_path,
                'updated_at' => $post->updated_at,
                'user_name' => $post->user->name,
                'email' => $post->user->email

            ]
        ], 200);
    }
} 
