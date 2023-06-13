<?php

namespace App\Http\Controllers;

use App\Models\Calons;
use App\Models\ImportPemilih;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function vote(Request $request, $IdPemilih)
    {
        //done
        //cek apakah IdPemilihan berstatus aktif atau gak
        $status = DB::table('importpemilih')
            ->select('IdStatus')
            ->where('IdPemilih', $IdPemilih)
            ->first();

        //jika status aktif maka akan melanjutkan respon
        if ($status && $status->IdStatus == 1) {

            //mengecek apakah IdKandidat yang di berikan ada pada tabel kandidat atau tidak
            $validator = Validator::make($request->all(), [
                'id_calon' => 'required|exists:calons,id_calon'
            ]);

            if ($validator->fails()) {

            }

            //mengecek apakah IdCalon yang di berikan ada pada tabel kandidat atau tidak
            $Pemilih = ImportPemilih::where('IdPemilih', $IdPemilih)->first();
            if (!$Pemilih) {
                // Jika Pemilihan tidak ditemukan, kirim respons error
                return response()->json(['message' => 'Pemilihan tidak ditemukan'], 404);
            }

            // Ambil user yang sedang login
            $IdUser = $request->Users->id_user;

            // Cek apakah user sudah melakukan voting pada pemilihan ini
            $existingVote = Voting::where('id_user', $IdUser)
                ->where('IdPemilih', $IdPemilih)
                ->first();

            if ($existingVote) {
                return response()->json(['error' => 'User has already voted for this selection'], 422);
            }

            // Cek apakah IdCalon yang diberikan memiliki IdPemilihan yang sama dengan parameter yang diberikan
            $IdCalon = $request->input('id_calon');
            $calons = Calons::where('id_calon', $IdCalon)
                ->where('IdPemilih', $IdPemilih)
                ->first();

            if (!$calons) {
                return response()->json(['error' => 'IdCalon does not exist in this Pemilihan'], 422);
            }

            // Buat voting baru
            $voting = new Voting();
            $voting->IdUser = $IdUser;
            $voting->IdCalon = $request->input('id_calon');
            $voting->IdPemilih = $IdPemilih;
            $voting->WaktuVote = now();
            $voting->save();

            return response()->json(['message' => 'Vote recorded successfully'], 200);
            //jika status pemilihan belum di mulai akan mengembalkan nilai else
        } else {
            return response()->json(['message' => 'Pemilihan Belum Di Mulai'], 422);
        }
    }

    public function dashboard($IdPemilih)
    {
        //menampilkan voting masuk
        $votemasuk = DB::table('voting')
            ->join('users', 'voting.IdUser', '=', 'users.IdUser')
            ->join('calons', 'voting.IdCalon', '=', 'calons.IdCalon')
            ->join('importpemilih', 'voting.IdPemilih', '=', 'importpemilih.IdPemilih')
            ->select(
                'voting.IdVoting',
                'users.nama as NamaPemilih',
                DB::raw('(SELECT nama FROM user WHERE users.IdUser = calons.IdUser) AS VoteKandidat'),
                'importpemilih.nama as NamaPemilihan',
                'voting.WaktuVote'
            )
            ->where('importpemilih.IdPemilih', $IdPemilih)
            ->get();

        if ($votemasuk->isEmpty()) {
            return response()->json(['msg' => 'Pemilihan tidak ditemukan'], 404);
        }

        //jumlah vote yang masuk ke kandidat
        $totalvotekandidat = DB::table('voting')
            ->join('calons', 'voting.IdCalon', '=', 'calons.IdCalon')
            ->join('users', 'calons.IdUser', '=', 'users.IdUser')
            ->select(
                'voting.IdCalon',
                DB::raw('(SELECT nama FROM user WHERE users.IdUser = calons.IdUser) AS VoteKandidat'),
                DB::raw('COUNT(*) as JumlahVote')
            )
            ->where('voting.IdPemilih', $IdPemilih)
            ->groupBy('voting.IdCalon', 'calons.IdUser')
            ->get();


        //total vote yang sudah masuk
        $totalvote = DB::table('voting')
            ->select(DB::raw('COUNT(*) as TotalVoting'))
            ->where('IdPemilih', $IdPemilih)
            ->get();


        //total user yang belum vote
        $belumvote = DB::table('user')
            ->select(DB::raw('COUNT(*) as JumlahVotingBelumDilakukan'))
            ->where('role', '<>', 'admin')
            ->whereNotIn('IdUser', function ($query) use ($IdPemilih) {
                $query->select('IdUser')
                    ->from('voting')
                    ->where('IdPemilih', $IdPemilih);
            })
            ->get();

        return response()->json([
            'VoteMasuk' => $votemasuk,
            'Voteperkandidat' => $totalvotekandidat,
            'totalvote' => $totalvote,
            'belumvote' => $belumvote,
        ]);
    }

}
