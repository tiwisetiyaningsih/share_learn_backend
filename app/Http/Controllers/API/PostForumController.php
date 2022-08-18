<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DataPostForum;
use App\Models\Like;
use App\Models\LikeForum;
use App\Models\Notif;
use App\Models\PostForum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostForumController extends Controller
{
    public function getPostbyLiked($user)
    {
        $likedpost = DataPostForum::join('likes_forum as l', 'data_post_forum.id_thread', 'l.id_post_thread')
            ->where('user_like', $user)
            ->select(
                'data_post_forum.*'
            )
            ->orderBy('l.created_at', 'DESC')
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $likedpost
        ], 200);
    }

    public function getPostbyUser($user_thread)
    {
        $post = DataPostForum::where('user_thread', $user_thread)->orderBy('updated_at', 'DESC')
            ->get();
        if ($post) {
            return response()->json([
                'massage' => 'success',
                'data' => $post,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function getIdPostbyuser($user)
    {
        $like = LikeForum::join('users as u', 'likes_forum.user_like', 'u.fullname')
            ->where('fullname', $user)
            ->distinct()
            ->select(
                'likes_forum.id_post_thread'
            )
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $like
        ], 200);
    }
    public function createPostForum(Request $request)
    {

        // 
        $base64_image = $request->file_thread;
        $image = base64_decode(preg_replace('#^data:image/jpeg;base64,#i', '', $base64_image));

        //
        $image_name = "forum-post-file-" . date('Y-m-d-') . md5(uniqid(rand(), true)); // image name generating with random number with 32 characters
        $filename = $image_name . '.' . 'jpg';
        //rename file name with random number
        $path = public_path('data_file/');
        //image uploading folder path
        file_put_contents($path . $filename, $image);

        // 
        $post_image = 'data_file/' . $filename;

        $tanggal = date('Y-m-d G:i:s');

        $post = new PostForum;
        $post->id_user_thread = $request->id_user_thread;
        $post->user_thread = $request->user_thread;
        $post->thread = $request->thread;
        $post->file_thread = $post_image;
        $post->created_at = $tanggal;
        $post->updated_at = $tanggal;
        $post->save();

        if ($post) {
            // 1. ambil data users ditaruh dalam satu variable
            $alluser = User::get();
            // 2. variable user tersebut looping menggunakan forech

            foreach ($alluser as $key => $value) {
                if($value->id_users != $request->id_user_thread){

                    $notif = new Notif;
                    $notif->user_notif = $request->user_thread;
                    $notif->id_to_user = $value->id_users;
                    $notif->to_user = $value->fullname;
                    $notif->notif = ', just shared a new post.';
                    $notif->id_post = 0;
                    $notif->id_post_forum = 0;
                    $notif->id_comment = 0;
                    $notif->save();

                    $post_data = [
                        "token" => $value->remember_token,
                        "message" => $request->user_thread . ', just shared a new post.'
                    ];
                    $send_notif = new SendNotificationController;
                    $send_notif->sendNotification($post_data);
                }
                // return $value->fullname;
            }
            
            // 3. tiap loopingan dia insert parameter id_to_user  dan to_user
            return response()->json([
                'massage' => 'success',
                'data' => $post,
            ]);
        } else {  
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function createNoImage(Request $request){
        $post = new PostForum;
        $post->id_user_thread = $request->id_user_thread;
        $post->user_thread = $request->user_thread;
        $post->thread = $request->thread;
        $post->file_thread = 'No Image';
        $post->save();
        if ($post) {
            // 1. ambil data users ditaruh dalam satu variable
            $alluser = User::get();
            // 2. variable user tersebut looping menggunakan forech

            foreach ($alluser as $key => $value) {
                if($value->id_users != $request->id_user_thread){

                    $notif = new Notif;
                    $notif->user_notif = $request->user_thread;
                    $notif->id_to_user = $value->id_users;
                    $notif->to_user = $value->fullname;
                    $notif->notif = ', just shared a new post.';
                    $notif->id_post = 0;
                    $notif->id_post_forum = 0;
                    $notif->id_comment = 0;
                    $notif->save();

                    $post_data = [
                        "token" => $value->remember_token,
                        "message" => $request->user_thread . ', just shared a new post.'
                    ];
                    $send_notif = new SendNotificationController;
                    $send_notif->sendNotification($post_data);
                }
                // return $value->fullname;
            }
            
            // 3. tiap loopingan dia insert parameter id_to_user  dan to_user
            return response()->json([
                'massage' => 'success',
                'data' => $post,
            ]);
        } else {  
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }
    public function getAllPost()
    {
        $allpost = DataPostForum::orderBy('created_at', 'DESC')
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $allpost
        ], 200);
    }

    public function delete($id)
    {
        $post = PostForum::where('id_thread', $id);
        $post->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $post
        ], 200);
    }

    public function seacrhPost($thread) 
    {
        return response()->json([
            'message' => 'success',
            'data' => DataPostForum::orderBy('created_at', 'DESC')
            ->where('thread', 'LIKE', '%'.$thread.'%')
            ->get()
        ], 200);
    }

    public function getNotifPostbyId($id_thread)
    {
        $post = DataPostForum::where('id_thread', $id_thread)
        ->get();
        if ($post) {
            return response()->json([
                'massage' => 'success',
                'data' => $post,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }
}