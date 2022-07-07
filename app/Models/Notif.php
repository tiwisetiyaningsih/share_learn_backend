<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = "notification";

    protected $fillable = ['id_notif','user_notif','notif'];
}