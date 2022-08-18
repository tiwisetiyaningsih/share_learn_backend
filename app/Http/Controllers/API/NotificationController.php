<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getNotif($id_to_user)
    {
        $notif = Notif::orderBy('created_at', 'DESC')
            ->where('id_to_user', $id_to_user)
            ->limit(5)
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $notif
        ], 200);
    }

    public function getAllNotif($id_to_user)
    {
        $notif = Notif::orderBy('created_at', 'DESC')
            ->where('id_to_user', $id_to_user)
            ->get();
        return response()->json([
            'massage'   => 'success',
            'data'      => $notif
        ], 200);
    }

    public function delete($id)
    {
        $notif = Notif::where('id_notif', $id);
        $notif->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $notif
        ], 200);
    }
}
