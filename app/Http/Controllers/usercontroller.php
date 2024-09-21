<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class usercontroller extends Controller
{
    public function getAllUsersExceptAuthenticated()
    {
        $authenticatedUserId = Auth::id(); // Get the authenticated user's ID

        $users = User::where('id', '!=', $authenticatedUserId)
                     ->get(['id', 'name', 'email', 'phone_number', 'age']);

        return response()->json($users);
    }

    public function getUserName($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['name' => $user->name]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getUserInfo($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'age' => $user->age
            ]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getAuthenticatedUserName()
    {
        $user = Auth::user(); // Get the authenticated user

        if ($user) {
            return response()->json([
                'id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'age' => $user->age
            ]);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function gettheauthprofilpic(){
        $user = Auth::user();

        if ($user && $user->profile_pic && file_exists(public_path($user->profile_pic))) {
            return response()->file(public_path($user->profile_pic));
        } else {

            return response()->file(public_path('profile_pics/default_pic.jpg'));
        }
    }


    public function getUserProfilePic($id){
        $user = User::find($id);

        if ($user && $user->profile_pic && file_exists(public_path($user->profile_pic))) {
            return response()->file(public_path($user->profile_pic));
        } else {
            
            return response()->file(public_path('profile_pics/default_pic.jpg'));
        }
    }


    public function getUserProfilePicUsingName($name){
        $user = User::where('name', $name)->first();

        if ($user && $user->profile_pic && file_exists(public_path($user->profile_pic))) {
            return response()->file(public_path($user->profile_pic));
        } else {
            return response()->file(public_path('profile_pics/default_pic.jpg'));
        }
    }


}
