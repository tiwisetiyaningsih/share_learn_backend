<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notif;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getCommentbyId($id)
    {
        $comment = Comment::where('id_post', $id)->get();
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

    public function getCommentbyIdComment($id_comment)
    {
        $comment = Comment::where('id_comment', $id_comment)->get();
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

    public function create(Request $request)
    {
        $validate = $request->validate([
            'user_comment' => ['string', 'required', 'max:255'],
            'comment' => ['string', 'required', 'max:255'],
            'id_post' => ['required'],
        ]);

        $datapost = Post::where('id', $request->id_post)->first();

        $comment = Comment::create([
            'user_comment' => $validate['user_comment'],
            'comment' => $validate['comment'],
            'id_post' => $validate['id_post'],
        ]);

        if ($comment) {
            // 1. ambil data users ditaruh dalam satu variable
            // 2. variable user tersebut looping menggunakan forech

            $alluser = User::where('fullname', $datapost->user_post)->first();
            // 2. variable user tersebut looping menggunakan forech
            if ($datapost->user_post != $request->user_comment) {
                $notif = new Notif;
                $notif->user_notif = $request->user_comment;
                $notif->id_to_user = $alluser->id_users;
                $notif->to_user = $datapost->user_post;
                $notif->id_post = $request->id_post;
                $notif->id_post_forum = 0;
                $notif->id_comment = 0;
                $notif->notif = ', reply your post.';
                $notif->save();

                $post_data = [
                    "token" => $alluser->remember_token,
                    "message" => $request->user_comment . ', reply your post.'
                ];
                $send_notif = new SendNotificationController;
                $send_notif->sendNotification($post_data);

                // return $value->fullname;
            }

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
        $comment = Comment::where('id_comment', $id);
        $comment->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $comment
        ], 200);
    }
}
