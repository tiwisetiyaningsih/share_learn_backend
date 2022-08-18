<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notif;
use App\Models\Post;
use App\Models\ReplyComment;
use App\Models\User;
use Illuminate\Http\Request;

class ReplyCommentController extends Controller
{

    public function create(Request $request)
    {
        $reply = new ReplyComment;
        $reply->id_comment = $request->id_comment;
        $reply->user_comment = $request->user_comment;
        $reply->id_user_reply_comment = $request->id_user_reply_comment;
        $reply->user_reply_comment = $request->user_reply_comment;
        $reply->reply_comment = $request->reply_comment;
        $reply->save();

        if ($reply) {
            // 1. ambil data users ditaruh dalam satu variable
            $user = User::where('fullname', $request->user_comment)->first();
                if ($request->user_comment != $request->user_reply_comment) {

                    $notif = new Notif;
                    $notif->user_notif = $request->user_reply_comment;
                    $notif->id_to_user = $user->id_users;
                    $notif->to_user = $request->user_comment;
                    $notif->id_post = 0 ;
                    $notif->id_post_forum = 0;
                    $notif->id_comment = $request->id_comment;
                    $notif->notif = ', reply to your comment.';
                    $notif->save();

                    $post_data = [
                        "token" => $user->remember_token,
                        "message" => $request->user_reply_comment . ', reply to your comment.'
                    ];
                    $send_notif = new SendNotificationController;
                    $send_notif->sendNotification($post_data);
                }
                // return $value->fullname;

            // 3. ti  ap loopingan dia insert parameter id_to_user  dan to_user
            return response()->json([
                'massage' => 'success',
                'data' => $reply,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function getReplybyIdComment($id)
    {
        $reply = ReplyComment::where('id_comment', $id)->get();
        if ($reply) {
            return response()->json([
                'massage' => 'success',
                'data' => $reply,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function delete($id_reply_comment)
    {
        $reply = ReplyComment::where('id_reply_comment', $id_reply_comment);
        $reply->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $reply
        ], 200);
    }

}
