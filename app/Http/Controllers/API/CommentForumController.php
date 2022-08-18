<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CommentForum;
use App\Models\Notif;
use App\Models\User;
use Illuminate\Http\Request;

class CommentForumController extends Controller
{
    public function getCommentbyId($id)
    {
        $comment = CommentForum::where('id_post_thread', $id)->get();
        if ($comment) {
            return response()->json([
                'massage' => 'success',
                'data' => $comment,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function createCommentImage(Request $request)
    {

        // 
        $base64_image = $request->file_comment;
        $image = base64_decode(preg_replace('#^data:image/jpeg;base64,#i', '', $base64_image));

        //
        $image_name = "forum-comment-file-" . date('Y-m-d-') . md5(uniqid(rand(), true)); // image name generating with random number with 32 characters
        $filename = $image_name . '.' . 'jpg';
        //rename file name with random number
        $path = public_path('data_file/');
        //image uploading folder path
        file_put_contents($path . $filename, $image);

        // 
        $post_image = 'data_file/' . $filename;

        $tanggal = date('Y-m-d G:i:s');

        $comment = new CommentForum;
        $comment->id_post_thread = $request->id_post_thread;
        $comment->id_user_post_thread = $request->id_user_post_thread;
        $comment->user_post_thread = $request->user_post_thread;
        $comment->id_user_comment = $request->id_user_comment;
        $comment->user_comment = $request->user_comment;
        $comment->comment = $request->comment;
        $comment->file_comment = $post_image;
        $comment->created_at = $tanggal;
        $comment->updated_at = $tanggal;
        $comment->save();

        if ($comment) {
            // 1. ambil data users ditaruh dalam satu variable
            $user = User::where('fullname', $request->user_post_thread)->first();
                if ($request->user_post_thread != $request->user_comment) {

                    $notif = new Notif;
                    $notif->user_notif = $request->user_comment;
                    $notif->id_to_user = $request->id_user_post_thread;
                    $notif->to_user = $request->user_post_thread;
                    $notif->id_post = 0 ;
                    $notif->id_post_forum = $request->id_post_thread;
                    $notif->id_comment = 0;
                    $notif->notif = ', commented on your post.';
                    $notif->save();

                    $post_data = [
                        "token" => $user->remember_token,
                        "message" => $request->user_comment . ', commented on your post.'
                    ];
                    $send_notif = new SendNotificationController;
                    $send_notif->sendNotification($post_data);
                }
                // return $value->fullname;

            // 3. ti  ap loopingan dia insert parameter id_to_user  dan to_user
            return response()->json([
                'massage' => 'success',
                'data' => $comment,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function createNoImage(Request $request){
        $comment = new CommentForum;
        $comment->id_post_thread = $request->id_post_thread;
        $comment->id_user_post_thread = $request->id_user_post_thread;
        $comment->user_post_thread = $request->user_post_thread;
        $comment->id_user_comment = $request->id_user_comment;
        $comment->user_comment = $request->user_comment;
        $comment->comment = $request->comment;
        $comment->file_comment = 'No Image';
        $comment->save();

        if ($comment) {
            // 1. ambil data users ditaruh dalam satu variable
            $user = User::where('fullname', $request->user_post_thread)->first();
                if ($request->user_post_thread != $request->user_comment) {

                    $notif = new Notif;
                    $notif->user_notif = $request->user_comment;
                    $notif->id_to_user = $request->id_user_post_thread;
                    $notif->to_user = $request->user_post_thread;
                    $notif->id_post = 0 ;
                    $notif->id_post_forum = $request->id_post_thread;
                    $notif->id_comment = 0;
                    $notif->notif = ', reply on your post.';
                    $notif->save();

                    $post_data = [
                        "token" => $user->remember_token,
                        "message" => $request->user_comment . ', reply on your post.'
                    ];
                    $send_notif = new SendNotificationController;
                    $send_notif->sendNotification($post_data);
                }
                // return $value->fullname;

            // 3. ti  ap loopingan dia insert parameter id_to_user  dan to_user
            return response()->json([
                'massage' => 'success',
                'data' => $comment,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function delete($id)
    {
        $comment = CommentForum::where('id_comment', $id);
        $comment->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $comment
        ], 200);
    }
}
