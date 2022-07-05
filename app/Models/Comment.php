<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";

    protected $fillable = ['id_post', 'user_comment', 'comment'];
}