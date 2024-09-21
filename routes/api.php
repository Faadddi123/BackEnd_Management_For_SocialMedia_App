<?php

use App\Http\Controllers\authentification;
use App\Http\Controllers\commentmanagement;
use App\Http\Controllers\displaycontroller;
use App\Http\Controllers\MediaController;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Route::post('/login', [authentification::class, 'login']);
    // Send a message
    Route::post('/sendmessage', [messagecontroller::class, 'sendMessage']);


    Route::post('/loadingmessages', [messagecontroller::class, 'loadMessages']);


    Route::get('/tokenvalidate', function () {
        return response()->json(['message' => 'Token is valid'], 200);
    });
    Route::get('/gettheauthprofilpic',[usercontroller::class,'gettheauthprofilpic']);
    Route::get('/getUserProfilePic/{id}',[usercontroller::class,'getUserProfilePic']);
    Route::get('/getUserProfilePicUsingName/{user_name}',[usercontroller::class,'getUserProfilePicUsingName']);
    Route::get('/users', [UserController::class,'getAllUsersExceptAuthenticated']);
    Route::get('/getusername/{id}', [UserController::class, 'getUserName']);
    Route::get('/getuserinfo/{id}', [UserController::class, 'getUserInfo']);
    Route::post('/importapost' , [postmanagement::class , 'store']);
    Route::get('/posts/display', [displaycontroller::class , 'displayPosts']);
    Route::get('/posts/displayVidPosts', [displaycontroller::class , 'displayVidPosts']);
    Route::get('/posts/displayProfile/{user_name}', [displaycontroller::class , 'displayProfile']);
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
    Route::get('/comments/displayed/{displayed_id}', [commentmanagement::class,'getCommentsByDisplayedId']);
    Route::post('/media/upload', [MediaController::class, 'upload']);
    Route::get('/media/stream/{mediaId}', [MediaController::class, 'stream']);
    Route::get('/media/check/{mediaId}', [MediaController::class, 'checkFileExists']);
    Route::get('/media/checkback/{mediaId}', [MediaController::class, 'getbackimages']);
    Route::get('/media/videoback/{mediaId}', [MediaController::class, 'getbackvideos']);
});

Route::middleware('auth:sanctum')->post('/logout', [authentification::class, 'logout']);
