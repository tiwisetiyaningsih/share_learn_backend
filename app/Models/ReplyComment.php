<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyComment extends Model
{
    protected $table = "reply_comment";

    protected $fillable = ['id_reply_comment','id_comment', 'user_comment', 'id_user_reply_comment', 'user_reply_comment', 'reply_comment'];
}
