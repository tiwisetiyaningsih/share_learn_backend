<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => 'required|string',
            "fullname" => 'required|string',
            "nis" => 'required|string',
            "email" => 'required|string',
            "password" => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
        $cekuser = User::where('username', $request-> username)
        ->first();
        if ($cekuser) {
            return response()->json([
                'message' => 'username telah terpakai',
                'data' => $cekuser,
            ]);
        }

        $encrypted = Hash::make($request->password);
        $user = User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'nis' => $request->nis,
            'email' => $request->email,
            'password' => $encrypted,
        ]);

        if ($user) {
            return response()->json([
                'message' => 'success',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'message' => 'failed',
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $user = DB::table('users')->where('username', $request->username)->first();
            // return $user ;
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message'   => 'data falid',
                    'data'      => $user
                ], 200);
            } else {
                return response()->json([
                    'message'   => 'password salah'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => 'username atau password salah'
            ], 200);
        }
    }

    // public function update(Request $request, $id_user)
    // {
    //     $validator = Validator::make($request->all(), [
    //         "username" => 'required|string',
    //         "fullname" => 'required|string',
    //         "nis" => 'required|string',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             $validator->errors()
    //         ], 400);
    //     }

    //     $user = User::find($id_user);
    //     // return $user;
    //     if ($user) {
            
    //         $user->update([
    //             "username" => $request->username,
    //             "fullname" => $request->fullname,
    //             "nis" => $request->nis
    //         ]);

    //         $user->save();

    //         return response()->json([
    //             'message'   => 'success',
    //             'data'      => $user
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message'   => 'There is no data found',
    //             'data'      => null
    //         ], 500);
    //     }
    // }
    
    public function update( Request $request, $id_user)
    {
        $user = User::where('id_users', $id_user)
        ->update([
            "username" => $request->username,
            "fullname" => $request->fullname,
            "nis" => $request->nis
        ]);
        return response()->json([
            'massage'   => 'success',
            'data'      => $user
        ], 200);
    }
}
