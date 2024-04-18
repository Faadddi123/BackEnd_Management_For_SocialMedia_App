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
             ->join('users', 'users.id', '=', 'displayed.user_id')
             ->select('posts.*', 'users.name as user_name','users.email as email', 'displayed.partage_id')
             ->latest('posts.created_at')
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
