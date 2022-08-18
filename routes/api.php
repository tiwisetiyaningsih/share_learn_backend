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

    Route::get('/readAll',
    [\App\Http\Controllers\API\PostController::class,
    'getAllPost2']);

    Route::post('/upload',
    [\App\Http\Controllers\API\PostController::class,
    'createPost']);

    Route::get('/readmapel/{mapel}',
    [\App\Http\Controllers\API\PostController::class,
    'getAllPostbyMapel']);

    Route::post('/updatePost/{id}',
    [\App\Http\Controllers\API\PostController::class,
    'updatePost']);

    Route::post('/updatePostPdf/{id}',
    [\App\Http\Controllers\API\PostController::class,
    'updatePostPdf']);

   Route::get('/delete/{id}',
   [\App\Http\Controllers\API\PostController::class,
   'delete']);

   Route::post('/uploadpdf',
   [\App\Http\Controllers\API\PostController::class,
   'createPostPdf']);

   Route::get('/likedpost/{user}',
   [\App\Http\Controllers\API\PostController::class,
   'getPostbyLiked']);

   Route::get('/IdPostbyUser/{user}',
   [\App\Http\Controllers\API\PostController::class,
   'getIdPostbyuser']);

   Route::get('/seacrhPost/{sub_judul_post}',
   [\App\Http\Controllers\API\PostController::class,
   'seacrhPost']);

   Route::get('/read/getNotifPostbyId/{id}',
   [\App\Http\Controllers\API\PostController::class,
   'getNotifPostbyId']);

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

    Route::post('/updateNotes/{id}',
    [\App\Http\Controllers\API\NotesController::class,
    'updateNotes']);

    Route::get('/delete/{id}',
    [\App\Http\Controllers\API\NotesController::class,
    'delete']);
});

Route::prefix('comment')->group(function(){

    Route::get('/read/{id}',
    [\App\Http\Controllers\API\CommentController::class,
    'getCommentbyId']);

    Route::post('/create',
    [App\Http\Controllers\API\CommentController::class,
    'create']);

    Route::get('/delete/{id}',
    [\App\Http\Controllers\API\CommentController::class,
    'delete']);

    Route::get('/getComment/{id_comment}',
    [App\Http\Controllers\API\CommentController::class,
    'getCommentbyIdComment']);

});

Route::prefix('reply')->group(function(){

    Route::post('/create',
    [\App\Http\Controllers\API\ReplyCommentController::class,
    'create']);

    Route::get('/getReply/{id}',
    [\App\Http\Controllers\API\ReplyCommentController::class,
    'getReplybyIdComment']);

    Route::get('/delete/{id_reply_comment}',
    [\App\Http\Controllers\API\ReplyCommentController::class,
    'delete']);

});

Route::prefix('like')->group(function(){

    Route::get('/read/{id}',
    [\App\Http\Controllers\API\LikeController::class,
    'getLikebyId']);

    Route::post('/create',
    [App\Http\Controllers\API\LikeController::class,
    'create']);

});

Route::prefix('notif')->group(function(){
    
    Route::get('/read/{id_to_user}',
    [\App\Http\Controllers\API\NotificationController::class,
    'getNotif']);

    Route::get('/readAll/{id_to_user}',
    [\App\Http\Controllers\API\NotificationController::class,
    'getAllNotif']);

    Route::get('/delete/{id}',
    [\App\Http\Controllers\API\NotificationController::class,
    'delete']);

});

Route::prefix('postForum')->group(function(){

    Route::post('/create',
    [\App\Http\Controllers\API\PostForumController::class,
    'createPostForum']);

    Route::post('/createnoimage',
    [\App\Http\Controllers\API\PostForumController::class,
    'createNoImage']);

    Route::get('/readAllpost',
    [\App\Http\Controllers\API\PostForumController::class,
    'getAllPost']);

    Route::get('/likedpost/{user}',
   [\App\Http\Controllers\API\PostForumController::class,
   'getPostbyLiked']);

   Route::get('/IdPostbyUser/{user}',
   [\App\Http\Controllers\API\PostForumController::class,
   'getIdPostbyuser']);

   Route::get('/delete/{id}',
   [\App\Http\Controllers\API\PostForumController::class,
   'delete']);

   Route::get('/seacrhPost/{thread}',
   [\App\Http\Controllers\API\PostForumController::class,
   'seacrhPost']);

   Route::get('/read/getNotifPostbyId/{id_thread}',
   [\App\Http\Controllers\API\PostForumController::class,
   'getNotifPostbyId']);

   Route::get('/read/getPostbyUser/{user_thread}',
   [\App\Http\Controllers\API\PostForumController::class,
   'getPostbyUser']);

});

Route::prefix('commentForum')->group(function(){

    Route::get('/read/{id}',
    [\App\Http\Controllers\API\CommentForumController::class,
    'getCommentbyId']);
 
    Route::post('/create',
    [\App\Http\Controllers\API\CommentForumController::class,
    'createCommentImage']);

    Route::post('/createnoimage',
    [\App\Http\Controllers\API\CommentForumController::class,
    'createNoImage']);

    Route::get('/delete/{id}',
    [\App\Http\Controllers\API\CommentForumController::class,
    'delete']);

});

Route::prefix('likeForum')->group(function(){

    Route::get('/read/{id}',
    [\App\Http\Controllers\API\LikeForumController::class,
    'getLikebyId']);

    Route::post('/create',
    [App\Http\Controllers\API\LikeForumController::class,
    'create']);

    

});
