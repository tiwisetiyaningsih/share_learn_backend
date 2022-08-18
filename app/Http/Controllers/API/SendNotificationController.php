<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendNotificationController extends Controller
{
    public function sendNotification ($request)
    {
        // $firebaseToken = 'fWV5Es-1SlGIlWiHTJuO9K:APA91bFosghHvlAtiHCswcC4MUZF86cNln2MFwV7ZtbNcViauCzj7N4vyqVKmtJBqqUFGXvsQhakvE5KIDkhRIek5lR3VCfBVLJ-W6P449r3GX_yonWyOpXLKPoInZIY3JlkESkDmksh';

        
        $firebaseToken = $request["token"];

        $SERVER_API_KEY = 'AAAAYca92yE:APA91bGKeoubr9SH_bTVtQW3D2-uC18Jf7SUAFcldtI3Ca0PAAJEYYWzU12u8qEIdi0LdSMAgkUO4os45U-MWG2fDcEwvtb6dmZJojS2H6_RcoHteKgXtoBO6e-JR0Aqj2nq19wJxiWL';

        $data = [
            "to" => $firebaseToken,
            "notification" => [
                "title" => 'Share Learn',
                "body" => $request["message"],
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $res = json_decode($response, true);

        return response()->json([
                'massage' => 'notif masuk',
                'data' => $res,
            ]);
    }
}