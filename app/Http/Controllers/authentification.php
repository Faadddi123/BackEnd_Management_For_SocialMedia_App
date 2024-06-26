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
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'phone_number' => 'required|string', // Consider adding regex for phone number validation
        'age' => 'required|integer|between:0,120',
        'profile_pic' => 'required|file|mimes:jpg,jpeg,png|max:10240', // Validation for profile picture
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
    }

    // Handle file upload for profile picture
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

    // Create the user with the role field
    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phone_number' => $request->input('phone_number'),
        'age' => $request->input('age'),
        'profile_pic' => 'profile_pics/' . $filename, // Store relative path in the database
        'password' => Hash::make($request->input('password')),
    ]);

    // Generate a token for the user
    $token = $user->createToken('Tawassol')->plainTextToken;

    // Return the token and user details
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
        // If the request reaches here, it means the token is valid,
        // as it has passed through the authentication middleware.
        return response()->json(['message' => 'Token is valid.'], 200);
    }

    public function login(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Return the token and user details
            return response()->json(['token' => $token, 'user' => $user], 200);
        } else {
            // Authentication failed...
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }



    public function logout(Request $request)
    {
        // Get the authenticated user's token
        $token = $request->user()->token();

        // Revoke the token to log the user out
        $token->revoke();

        // Return a response indicating the user has been logged out
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
