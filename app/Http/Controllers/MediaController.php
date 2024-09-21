<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['media' => 'required|file|image|max:10240']);
        $file = $request->file('media');
        $fileName = $file->hashName();
        $path = $file->storeAs('media', $fileName, 'public');
        return response()->json(['message' => 'File uploaded successfully', 'fileName' => $fileName], 200);
    }


    public function stream($mediaId)
    {
        $media = Post::findOrFail($mediaId);
        $path = storage_path('app/public/media' . $media->file_path);
        if (!File::exists($path)) {
            abort(404, 'Media not found.');
        }
        return response()->file($path);
    }

    public function checkFileExists($fileName)
    {
        $path = storage_path('app/public/media/' . $fileName);

        if (File::exists($path)) {
            return response()->file($path);
        } else {
            return response()->json(['success' => false, 'message' => 'File does not exist.'], 404);
        }
    }
    public function getbackimages($fileName)
    {
        $path = storage_path('app/public/images/' . $fileName);

        if (File::exists($path)) {
            return response()->file($path);
        } else {
            return response()->json(['success' => false, 'message' => 'File does not exist.'], 404);
        }
    }
    public function getbackvideos($fileName)
    {
        $path = storage_path('app/public/videos/' . $fileName);

        if (File::exists($path)) {
            return response()->file($path); 
        } else {
            return response()->json(['success' => false, 'message' => 'File does not exist.'], 404);
        }
    }
}
