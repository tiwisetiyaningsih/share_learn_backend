<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller 
{
    public function getLikebyId($id)
    {
        $like = Like::where('id_post',$id)->get();
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
        $like = Like::where('user_like',$request->user_like)->where('id_post',$request->id_post)->first();

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
        }else {
            // return 'proses 2';
            $validate = $request->validate([
                'user_like' => ['string', 'required', 'max:255'],
                'id_post' => ['required'],
            ]);
    
            $like = Like::create([
                'user_like' => $validate['user_like'],
                'id_post' => $validate['id_post'],
            ]);
    
            if ($like) {
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
