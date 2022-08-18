<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostForum extends Model
{
    protected $table = "forum";

    protected $fillable = ['id_thread','id_user_thread','user_thread','thread','file_thread'];
}