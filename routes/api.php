<?php

use App\Http\Controllers\authentification;
use App\Http\Controllers\commentmanagement;
use App\Http\Controllers\displaycontroller;
use App\Http\Controllers\messagecontroller;
use App\Http\Controllers\postmanagement;
use App\Http\Controllers\sharecontroller;
use App\Http\Controllers\usercontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Registration
Route::post('/register', [authentification::class, 'register']);

// Login
Route::post('/login', [authentification::class, 'login']);
//--------------------------------------------------------


Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});



//-------------------------------------------------------
// Protected routes with Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Fetch user details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Route::post('/login', [authentification::class, 'login']);
    // Send a message
    Route::post('/sendmessage', [messagecontroller::class, 'sendMessage']);

    // Load messages
    Route::post('/loadingmessages', [messagecontroller::class, 'loadMessages']);

    // Token validation (assuming you want to check if token is valid)
    Route::get('/tokenvalidate', function () {
        return response()->json(['message' => 'Token is valid'], 200);
    });
    Route::get('/users', [UserController::class,'getAllUsersExceptAuthenticated']);
    Route::get('/getusername/{id}', [UserController::class, 'getUserName']);
    Route::get('/getuserinfo/{id}', [UserController::class, 'getUserInfo']);
    Route::post('/importapost' , [postmanagement::class , 'store']);
    Route::get('/posts/display', [displaycontroller::class , 'displayPosts']);
    Route::post('/message/delete/{id}', [messagecontroller::class, 'deleteMessage']);
    Route::post('/message/update/{id}', [messagecontroller::class, 'updateMessage']);
    Route::get('/posts/{id}', [PostManagement::class, 'show']);
    Route::put('/posts/{id}', [PostManagement::class, 'update']);
    Route::delete('/posts/{id}', [PostManagement::class, 'delete']);
    Route::get('/getusernameoftheauth' , [usercontroller::class, 'getAuthenticatedUserName']);
    Route::post('/importashare' , [sharecontroller::class , 'storeasharepost']);
    Route::get('/getthepostinfo/{id}' , [PostManagement::class, 'getpostinfo']);
    Route::post('/putacomment', [commentmanagement::class, 'create']);
    Route::put('/comments/{id}', [commentmanagement::class, 'update']);
    Route::delete('/comments/{id}', [commentmanagement::class, 'delete']);
});

// Assuming you might also want a logout route
Route::middleware('auth:sanctum')->post('/logout', [authentification::class, 'logout']);
