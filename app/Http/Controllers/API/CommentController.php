<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller 
{
    public function getCommentbyId($id)
    {
        $comment = Comment::where('id_post',$id)->get();
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

        $comment = Comment::create([
            'user_comment' => $validate['user_comment'],
            'comment' => $validate['comment'],
            'id_post' => $validate['id_post'],
        ]);

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
}
