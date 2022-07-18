<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DataPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller 
{
    public function getAllPost()
    {
        $allpost = DataPost::orderBy('jumlah_like','DESC')
        ->limit(10)
        ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $allpost
        ], 200);
    }

    public function getAllPostbyMapel($mapel) 
    {
        $postbymapel = DataPost::where('mapel_post', $mapel)
        ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $postbymapel
        ], 200);
    }

    public function getPostbyId( $user_post)
    {
        $post = DataPost::where('user_post', $user_post)->get();
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
        $post->user_post=$request->user_post;
        $post->file_post=$post_image;
        $post->type_file='Image';
        $post->mapel_post=$request->mapel_post;
        $post->judul_post=$request->judul_post;
        $post->sub_judul_post=$request->sub_judul_post;
        $post->created_at=$tanggal;
        $post->updated_at=$tanggal;
        $post->save();

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

    public function createPostPdf (Request $request) 
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
            $nama_file = "post-file-" . date('Y-m-d-') . md5(uniqid(rand(), true))."." . $file_extension;

            $tujuan_upload = 0;
            if ($file_extension == 'pdf') {
                $tujuan_upload = public_path('data_file');
            }
            
            $file->move($tujuan_upload, $nama_file);
            $post_image = 'data_file/' . $nama_file;
            $tanggal = date('Y-m-d G:i:s');

            $post = new Post;
            $post->user_post=$request->user_post;
            $post->file_post=$post_image;
            $post->type_file='Pdf';
            $post->mapel_post=$request->mapel_post;
            $post->judul_post=$request->judul_post;
            $post->sub_judul_post=$request->sub_judul_post;
            $post->created_at=$tanggal;
            $post->updated_at=$tanggal;
            $post->save();

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
        return response()->json([
            'massage' => 'data file tidak ada',
        ]);
    }

    public function updatePost(Request $request, $id) 
    {
        $post = Post::where('id', $id)
        ->update([
            "mapel_post" => $request->mapel_post,
            "judul_post" => $request->judul_post,
            "sub_judul_post" => $request->sub_judul_post,
            "file_post" => $request->file_post
        ]);
        return response()->json([
            'massage'   => 'success',
            'data'      => $post
        ], 200);
    }

    public function updatePostPdf(Request $request, $id) 
    {
        $post = Post::where('id', $id)
        ->update([
            "mapel_post" => $request->mapel_post,
            "judul_post" => $request->judul_post,
            "sub_judul_post" => $request->sub_judul_post,
            "file_post" => $request->file_post
        ]);
        return response()->json([
            'massage'   => 'success',
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
}