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

    public function updateNotes( Request $request, $id)
    {
        $notes = Notes::where('id_notes', $id)
        ->update([
            "judul_notes" => $request->judul_notes,
            "sub_judul_notes" => $request->sub_judul_notes,
            "notes" => $request->notes
        ]);
        return response()->json([
            'massage'   => 'success',
            'data'      => $notes
        ], 200);
    }

    public function delete($id)
    {
        $notes = Notes::where('id_notes',$id);
        $notes->delete();
        return response()->json([
            'massage'   => 'success',
            'data'      => $notes
        ], 200);
    }
    
}