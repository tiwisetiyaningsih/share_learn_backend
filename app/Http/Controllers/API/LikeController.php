<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Notif;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function getLikebyId($id)
    {
        $like = Like::where('id_post', $id)->get();
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
        $like = Like::where('user_like', $request->user_like)->where('id_post', $request->id_post)->first();

        //jika data tersedia atau tidak 0, maka melakukan proses delete
        if ($like) {

            //return 'proses 1';
            $unlike = Like::where('id_post', $request->id_post)->where('user_like', $request->user_like)->delete();

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
            $validate = $request->validate([
                'user_like' => ['string', 'required', 'max:255'],
                'id_post' => ['required'],
            ]);

            $datapost = Post::where('id', $request->id_post)->first();

            $like = Like::create([
                'user_like' => $validate['user_like'],
                'id_post' => $validate['id_post'],
            ]);

            if ($like) {
                $alluser = User::where('fullname', $datapost->user_post)->first();
                // 2. variable user tersebut looping menggunakan forech
                if ($datapost->user_post != $request->user_like) {
                    $notif = new Notif;
                    $notif->user_notif = $request->user_like;
                    $notif->id_to_user = $alluser->id_users;
                    $notif->to_user = $datapost->user_post;
                    $notif->id_post = $request->id_post;
                    $notif->id_post_forum = 0;
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
