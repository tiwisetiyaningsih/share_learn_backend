<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller 
{
    public function getAllPost()
    {
        
        return response()->json([
            'massage'   => 'success',
            'data'      => Post::all()
        ], 200);
    }

    public function getPostbyId( $user_post)
    {
        $post = Post::where('user_post', $user_post)->get();
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
        $base64_image = $request->gambar;
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

        // $thread = Thread::create([
        //     'user' => $request->user,
        //     'gambar' => $post_image,
        //     'komunitas' => $request->komunitas,
        //     'judul' => $request->judul
        // ]);
        $tanggal = date('Y-m-d G:i:s');
        $post = DB::table('post')
        ->insert([
            'user_post' => $request->user_post,
            'file_post' => $post_image,
            'mapel_post' => $request->mapel_post,
            'judul_post' => $request->judul_post,
            'sub_judul_post' => $request->sub_judul_post,
            'created_at' => $tanggal,
            'updated_at' => $tanggal
        ]);

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