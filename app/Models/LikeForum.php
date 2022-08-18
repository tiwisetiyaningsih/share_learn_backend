<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeForum extends Model
{
    protected $table = "likes_forum";

    protected $fillable = ['id_like','id_post_thread', 'id_user_post_thread','user_post_thread','id_user_like', 'user_like'];
}