<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DataPost;
use App\Models\Like;
use App\Models\Notif;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getAllPost()
    {
        $allpost = DataPost::orderBy('jumlah_like', 'DESC')
            ->limit(10)
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $allpost
        ], 200);
    }

    public function getAllPost2()
    {
        $allpost = DataPost::orderBy('jumlah_like', 'DESC')
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $allpost
        ], 200);
    }

    public function getPostbyLiked($user)
    {
        $likedpost = DataPost::join('likes as l', 'data_post.id', 'l.id_post')
            ->where('user_like', $user)
            ->select(
                'data_post.*'
            )
            ->orderBy('l.created_at', 'DESC')
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $likedpost
        ], 200);
    }

    public function getIdPostbyuser($user)
    {
        $star = Like::join('users as u', 'likes.user_like', 'u.fullname')
            ->where('fullname', $user)
            ->distinct()
            ->select(
                'likes.id_post'
            )
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $star
        ], 200);
    }

    public function getAllPostbyMapel($mapel)
    {
        $postbymapel = DataPost::where('mapel_post', $mapel)->orderBy('updated_at', 'DESC')
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $postbymapel
        ], 200);
    }

    public function getPostbyId($user_post)
    {
        $post = DataPost::where('user_post', $user_post)->orderBy('updated_at', 'DESC')
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

    public function getNotifPostbyId($id)
    {
        $post = DataPost::where('id', $id)
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

    public function createPost(Request $request)
    {

        // 
        $base64_image = $request->file_post;
        $image = base64_decode(preg_replace('#^data:image/jpeg;base64,#i', '', $base64_image));

        //
        $image_name = "post-file-" . date('Y-m-d-') . md5(uniqid(rand(), true)); // image name generating with random number with 32 characters
        $filename = $image_name . '.' . 'jpg';
        //rename file name with random number
        $path = public_path('data_file/');
        //image uploading folder path
        file_put_contents($path . $filename, $image);

        // 
        $post_image = 'data_file/' . $filename;

        $tanggal = date('Y-m-d G:i:s');

        $post = new Post;
        $post->user_post = $request->user_post;
        $post->file_post = $post_image;
        $post->type_file = 'Image';
        $post->mapel_post = $request->mapel_post;
        $post->judul_post = $request->judul_post;
        $post->sub_judul_post = $request->sub_judul_post;
        $post->created_at = $tanggal;
        $post->updated_at = $tanggal;
        $post->save();

        if ($post) {
            // 1. ambil data users ditaruh dalam satu variable
            $alluser = User::get();
            // 2. variable user tersebut looping menggunakan forech

            foreach ($alluser as $key => $value) {
                if ($value->id_users != $request->id_user) {

                    $notif = new Notif;
                    $notif->user_notif = $request->user_post;
                    $notif->id_to_user = $value->id_users;
                    $notif->to_user = $value->fullname;
                    $notif->notif = ', just shared a new post.';
                    $notif->id_post = 0;
                    $notif->id_post_forum = 0;
                    $notif->id_comment = 0;
                    $notif->save();

                    $post_data = [
                        "token" => $value->remember_token,
                        "message" => $request->user_post . ', just shared a new post.'
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

    public function createPostPdf(Request $request)
    {

        if ($request->file('file_post')) {

            $tanggal_upload = date("Y-m-d G-i-s");

            $file = $request->file('file_post');

            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $file_real_path = $file->getRealPath();
            $file_size = $file->getSize();
            $file_mime_type = $file->getMimeType();

            // custom nama file
            $nama_file = "post-file-" . date('Y-m-d-') . md5(uniqid(rand(), true)) . "." . $file_extension;

            $tujuan_upload = 0;
            if ($file_extension == 'pdf') {
                $tujuan_upload = public_path('data_file');
            }

            $file->move($tujuan_upload, $nama_file);
            $post_pdf = 'data_file/' . $nama_file;
            $tanggal = date('Y-m-d G:i:s');

            $post = new Post;
            $post->user_post = $request->user_post;
            $post->file_post = $post_pdf;
            $post->type_file = 'Pdf';
            $post->mapel_post = $request->mapel_post;
            $post->judul_post = $request->judul_post;
            $post->sub_judul_post = $request->sub_judul_post;
            $post->created_at = $tanggal;
            $post->updated_at = $tanggal;
            $post->save();

            if ($post) {
                // 1. ambil data users ditaruh dalam satu variable
                $alluser = User::get();
                // 2. variable user tersebut looping menggunakan forech
                foreach ($alluser as $key => $value) {
                    if ($value->id_users != $request->id_user) {

                        $notif = new Notif;
                        $notif->user_notif = $request->user_post;
                        $notif->id_to_user = $value->id_users;
                        $notif->to_user = $value->fullname;
                        $notif->notif = ', just shared a new post.';
                        $notif->id_post = 0;
                        $notif->id_post_forum = 0;
                        $notif->id_comment = 0;
                        $notif->save();

                        $post_data = [
                            "token" => $value->remember_token,
                            "message" => $request->user_post . ', just shared a new post.'
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
        return response()->json([
            'massage' => 'data file tidak ada',
        ]);
    }

    public function updatePost(Request $request, $id)
    {
        $data_post = Post::where('id', $id)->first();
        $file_post = $data_post->file_post;
        if ($request->type_file == 'Image') {
            if ($request->file_post != '') {
                $file_post = '';
                $base64_image = $request->file_post;
                $image = base64_decode(preg_replace('#^data:image/jpeg;base64,#i', '', $base64_image));

                //
                $image_name = "post-file-" . date('Y-m-d-') . md5(uniqid(rand(), true)); // image name generating with random number with 32 characters
                $filename = $image_name . '.' . 'jpg';
                //rename file name with random number
                $path = public_path('data_file/');
                //image uploading folder path
                file_put_contents($path . $filename, $image);

                $file_post = 'data_file/' . $filename;
            }
        }


        $post = Post::where('id', $id)
            ->update([
                "mapel_post" => $request->mapel_post,
                "judul_post" => $request->judul_post,
                "sub_judul_post" => $request->sub_judul_post,
                "file_post" => $file_post,
                "type_file" => $request->type_file
            ]);
        return response()->json([
            'massage'   => 'success update',
            'data'      => $post
        ], 200);
    }

    public function updatePostPdf(Request $request, $id)
    {
        $data_post = Post::where('id', $id)->first();
        $file_post = $data_post->file_post;
        if ($request->type_file == 'Pdf') {
            if ($request->file_post != '') {
                $file_post = '';
                $file = $request->file('file_post');

                $file_name = $file->getClientOriginalName();
                $file_extension = $file->getClientOriginalExtension();
                $file_real_path = $file->getRealPath();
                $file_size = $file->getSize();
                $file_mime_type = $file->getMimeType();

                // custom nama file
                $nama_file = "post-file-" . date('Y-m-d-') . md5(uniqid(rand(), true)) . "." . $file_extension;

                $tujuan_upload = 0;
                if ($file_extension == 'pdf') {
                    $tujuan_upload = public_path('data_file');
                }

                $file->move($tujuan_upload, $nama_file);
                $file_post = 'data_file/' . $nama_file;
            }
        }

        $post = Post::where('id', $id)
            ->update([
                "mapel_post" => $request->mapel_post,
                "judul_post" => $request->judul_post,
                "sub_judul_post" => $request->sub_judul_post,
                "file_post" => $file_post,
                "type_file" => $request->type_file
            ]);
        return response()->json([
            'massage'   => 'success update',
            'data'      => $post
        ], 200);
    }

    public function delete($id)
    {
        $post = Post::where('id', $id);
        $post->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $post
        ], 200);
    }
    public function seacrhPost($sub_judul_post)
    {
        return response()->json([
            'message' => 'success',
            'data' => DataPost::orderBy('created_at', 'DESC')
                ->where('sub_judul_post', 'LIKE', '%' . $sub_judul_post . '%')
                ->get()
        ], 200);
    }
}
