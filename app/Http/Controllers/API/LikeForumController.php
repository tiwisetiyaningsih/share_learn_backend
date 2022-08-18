<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LikeForum;
use App\Models\Notif;
use App\Models\Post;
use App\Models\PostForum;
use App\Models\User;
use Illuminate\Http\Request;

class LikeForumController extends Controller
{
    public function getLikebyId($id)
    {
        $like = LikeForum::where('id_post_thread', $id)->get();
        if ($like) {
            return response()->json([
                'massage' => 'success',
                'data' => $like,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function create(Request $request)
    {
        //check ke database data dari request ada tau tidak di dalam table like
        $like = LikeForum::where('user_like', $request->user_like)->where('id_post_thread', $request->id_post_thread)->first();

        //jika data tersedia atau tidak 0, maka melakukan proses delete
        if ($like) {

            //return 'proses 1';
            $unlike = LikeForum::where('id_post_thread', $request->id_post_thread)->where('user_like', $request->user_like)->delete();

            if ($unlike) {
                return response()->json([
                    'massage' => 'success unlike',
                ]);
            } else {
                return response()->json([
                    'massage' => 'failed',
                ]);
            }
            //jika data tidak tersedia , maka dia melakukan proses create like 
        } else {
            // return 'proses 2';

            $datapost = PostForum::where('id_thread', $request->id_post_thread)->first();

            $like = new LikeForum;
            $like->id_post_thread = $request->id_post_thread;
            $like->id_user_post_thread = $datapost->id_user_thread;
            $like->user_post_thread = $datapost->user_thread;
            $like->id_user_like = $request->id_user_like;
            $like->user_like = $request->user_like;
            $like->save();

            if ($like) {
                $alluser = User::where('id_users', $request->id_user_post_thread)->first();
                // 2. variable user tersebut looping menggunakan forech
                if ($datapost->user_thread != $request->user_like) {
                    $notif = new Notif;
                    $notif->user_notif = $request->user_like;
                    $notif->id_to_user = $datapost->id_user_thread;
                    $notif->to_user = $datapost->user_thread;
                    $notif->id_post = 0;
                    $notif->id_post_forum = $request->id_post_thread;
                    $notif->id_comment = 0;
                    $notif->notif = ', liked your post.';
                    $notif->save();

                    $post_data = [
                        "token" => $alluser->remember_token,
                        "message" => $request->user_like . ', liked your post.'
                    ];
                    $send_notif = new SendNotificationController;
                    $send_notif->sendNotification($post_data);

                    // return $value->fullname;

                }
                // 3. ti  ap loopingan dia insert parameter id_to_user  dan to_user
                return response()->json([
                    'massage' => 'success like',
                    'data' => $like,
                ]);
            } else {
                return response()->json([
                    'massage' => 'failed',
                ]);
            }
        }
    }
}
