<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPost extends Model
{
    protected $table = "data_post";

    protected $fillable = ['id','user_post','file_post','mapel_post','judul_post','sub_judul_post','type_file', 'jumlah_like', 'jumlah_comment'];
}