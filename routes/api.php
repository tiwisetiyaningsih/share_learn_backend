<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function(){
    
    Route::post('/register', 
    [\App\Http\Controllers\API\UserController::class,'create']);
    
    Route::post('/login',
    [\App\Http\Controllers\API\UserController::class,'login']);

    Route::post('/update/{id_user}',
    [\App\Http\Controllers\API\UserController::class,
    'update']);

});

Route::prefix('post')->group(function(){

    Route::get('/read/{user_post}',
    [\App\Http\Controllers\API\PostController::class,
    'getPostbyId']);

    Route::get('/read',
    [\App\Http\Controllers\API\PostController::class,
    'getAllPost']);

    Route::post('/upload',
    [\App\Http\Controllers\API\PostController::class,
    'createPost']);

});

Route::prefix('notes')->group(function(){

    Route::post('/create',
    [\App\Http\Controllers\API\NotesController::class,
    'create']);
    
    Route::get('/read/{user_notes}',
    [\App\Http\Controllers\API\NotesController::class,
    'getNotesbyId']);

    Route::post('/update/{id_notes}',
    [\App\Http\Controllers\API\NotesController::class,
    'update']);
});

Route::prefix('comment')->group(function(){

    Route::get('/read/{id}',
    [\App\Http\Controllers\API\CommentController::class,
    'getCommentbyId']);

    Route::post('/create',
    [App\Http\Controllers\API\CommentController::class,
    'create']);

});

Route::prefix('like')->group(function(){

    Route::get('/read/{id}',
    [\App\Http\Controllers\API\LikeController::class,
    'getLikebyId']);

    Route::post('/create',
    [App\Http\Controllers\API\LikeController::class,
    'create']);

});
