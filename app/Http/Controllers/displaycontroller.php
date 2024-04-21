<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Displayed;

class displaycontroller extends Controller
{
    public function displayPosts()
    {
        $posts = Post::join('displayed', 'posts.id', '=', 'displayed.post_id')
             ->join('users as sharer', 'sharer.id', '=', 'displayed.user_id')
             ->join('users as original_poster', 'original_poster.id', '=', 'posts.user_id')
             ->join('post_partaged', 'post_partaged.id', '=', 'displayed.partage_id')
             ->select(
                 'posts.*',
                 'sharer.name as user_name', 'sharer.email as email',
                 'original_poster.name as original_user_name', 'original_poster.email as original_user_email',
                 'displayed.partage_id','displayed.id as displayed_id',
                 'post_partaged.text',
                 'post_partaged.updated_at as partaged_updated_at'
             )
             ->latest('displayed.created_at')
             ->take(10)
             ->get();

        return response()->json($posts);
    }

    public function createDisplayed(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
            'partage_id' => 'required|integer',
        ]);

        $displayed = Displayed::create($validatedData);

        return response()->json($displayed, 201);
    }

    public function updateDisplayed(Request $request, $id)
    {
        $displayed = Displayed::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'integer',
            'post_id' => 'integer',
            'partage_id' => 'integer',
        ]);

        $displayed->update($validatedData);

        return response()->json($displayed);
    }


    public function deleteDisplayed($id)
    {
        $displayed = Displayed::findOrFail($id);
        $displayed->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
