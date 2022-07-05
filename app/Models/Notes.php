<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = "notes";

    protected $fillable = ['id_notes','user_notes','judul_notes','sub_judul_notes', 'notes'];
}