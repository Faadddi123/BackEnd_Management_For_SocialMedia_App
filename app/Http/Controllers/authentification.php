<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class authentification extends Controller
{
    public function register(Request $request)
{

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'phone_number' => 'required|string',
        'age' => 'required|integer|between:0,120',
        'profile_pic' => 'required|file|mimes:jpg,jpeg,png|max:10240',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
    }


    if ($request->hasFile('profile_pic')) {
        $file = $request->file('profile_pic');
        $directory = public_path('profile_pics');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);
    } else {
        return response()->json(['error' => 'Profile picture is required'], 422);
    }


    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phone_number' => $request->input('phone_number'),
        'age' => $request->input('age'),
        'profile_pic' => 'profile_pics/' . $filename,
        'password' => Hash::make($request->input('password')),
    ]);


    $token = $user->createToken('Tawassol')->plainTextToken;


    return response()->json(['token' => $token, 'user' => $user], 201);
}

    /**
     * Check if the token is valid.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTokenIsValid(Request $request)
    {

        return response()->json(['message' => 'Token is valid.'], 200);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;


            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {

            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }



    public function logout(Request $request)
    {

        $token = $request->user()->token();


        $token->revoke();

        
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
