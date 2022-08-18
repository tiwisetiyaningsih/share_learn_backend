<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPostForum extends Model
{
    protected $table = "data_post_forum";

    protected $fillable = ['id_thread','id_user_thread','user_thread','thread','file_thread','jumlah_like', 'jumlah_comment'];
}