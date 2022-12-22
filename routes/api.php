<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactsController;  
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeCommentController;
use App\Http\Controllers\CommentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/register", [PassportAuthController::class, "register"]);
Route::post("/login", [PassportAuthController::class, "login"]);

Route::middleware('auth:api')->group(function () {

    Route::get("/getProfile", [PassportAuthController::class, "getProfile"]);
    Route::post("/updateProfileImage", [PassportAuthController::class, "updateProfileImage"]);
    Route::post("/updateUserName", [PassportAuthController::class, "updateUserName"]);

    Route::post("/addComment", [CommentController::class, "addComment"]);
    Route::post("/getPostComment", [CommentController::class, "getPostComment"]);
    Route::post("/deleteComment", [CommentController::class, "deleteComment"]);
    Route::post("/updateComment", [CommentController::class, "updateComment"]);

    Route::post("/addPost", [PostController::class, "addPost"]);
    Route::get("/getAllPosts", [PostController::class, "getAllPosts"]);
    Route::post("/deletePost", [PostController::class, "deletePost"]);
    Route::post("/updatePost", [PostController::class, "updatePost"]);

    Route::post("/toggleLikeValue", [PostController::class, "toggleLikeValue"]);
    Route::post("/toggleLikeValueForComment", [LikeCommentController::class, "toggleLikeValueForComment"]);
});

