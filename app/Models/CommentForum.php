<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentForum extends Model
{
    protected $table = "comment_forum";

    protected $fillable = ['id_comment','id_post_thread', 'id_user_post_thread','user_post_thread','id_user_comment', 'user_comment', 'comment','file_comment'];
}