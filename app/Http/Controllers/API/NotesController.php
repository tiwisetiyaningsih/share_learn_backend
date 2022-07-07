<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class NotesController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_notes" => 'required|string',
            "judul_notes" => 'required|string',
            "sub_judul_notes" => 'required|string',
            "notes" => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }
       
        $notes = Notes::create([
            'user_notes' => $request->user_notes,
            'judul_notes' => $request->judul_notes,
            'sub_judul_notes' => $request->sub_judul_notes,
            'notes' => $request->notes,
        ]);

        if ($notes) {
            return response()->json([
                'message' => 'success',
                'data' => $notes,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }

    public function getNotesbyId( $user_notes)
    {
        $notes = Notes::where('user_notes', $user_notes)->get();
        if ($notes) {
            return response()->json([
                'massage' => 'success',
                'data' => $notes,
            ]);
        } else {
            return response()->json([
                'massage' => 'failed',
            ]);
        }
    }
    public function update(Request $request, $id_notes)
    {
        $validator = Validator::make($request->all(), [
            "judul_notes" => 'required|string',
            "sub_judul_notes" => 'required|string',
            "notes" => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 400);
        }

        $notes = Notes::find($id_notes);
        
        if ($notes) {
            $notes->update([
                "judul_notes" => $request->judul_notes,
                "sub_judul_notes" => $request->sub_judul_notes,
                "notes" => $request->notes
            ]);

            $notes->save();

            return response()->json([
                'message'   => 'success',
                'data'      => $notes
            ], 200);
        } else {
            return response()->json([
                'message'   => 'There is no data found',
                'data'      => null
            ], 500);
        }
    }
}