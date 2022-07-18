<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "post";

    protected $fillable = ['id','user_post','file_post','mapel_post','judul_post','sub_judul_post'];
}