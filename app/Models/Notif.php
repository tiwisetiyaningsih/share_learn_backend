<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    protected $table = "notification";

    protected $fillable = ['id_notif','user_notif','id_to_user', 'to_user','notif', 'id_post', 'id_post_forum'];
}