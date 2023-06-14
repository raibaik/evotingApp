<?php

namespace App\Http\Controllers;

use App\Models\Calons;
use App\Models\HasilVoting; // Mengimpor model HasilVoting yang benar
use App\Models\ImportPemilih;

use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function vote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id',
            'id_calon' => 'required|exists:calons,id_calon',
            'IdPemilih' => 'required|exists:importpemilih,IdPemilih',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $votingData = $validator->validated();

        // Cek apakah user sudah melakukan voting sebelumnya
        $existingVote = Voting::where('id_user', $votingData['id_user'])
            ->where('IdPemilih', $votingData['IdPemilih'])
            ->first();

        if ($existingVote) {
            return response()->json([
                'error' => 'User has already voted',
            ], 409);
        }

        // Buat entitas Voting baru
        $voting = Voting::create($votingData);

        // Update total suara pada entitas HasilVoting
        $hasilVoting = HasilVoting::where('id_calon', $votingData['id_calon'])->first();

        if ($hasilVoting) {
            $hasilVoting->total_suara += 1;
            $hasilVoting->save();
        } else {
            HasilVoting::create([
                'id_calon' => $votingData['id_calon'],
                'total_suara' => 1,
            ]);
        }

        return response()->json([
            'message' => 'Vote has been recorded',
        ], 200);
    }

    public function hasilVoting() // Perbaikan penamaan metode menjadi hasilVoting (huruf 'h' kecil)
    {
        $hasilVoting = HasilVoting::with('calon')->get();

        return response()->json([
            'data' => $hasilVoting,
        ], 200);
    }
}