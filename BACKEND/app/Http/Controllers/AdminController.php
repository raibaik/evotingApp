<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Calons;
use App\Models\ImportPemilih;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nim' => 'required',
            'kelas' => 'required',
            'prodi' => 'required',
            'voting' => 'required',
            'role' => 'required|in:admin,user',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        $user = $validator->validated();

        User::create($user);

        return response()->json([
            'data' => [
                'msg' => 'Berhasil Login',
                'nama' => $user['nama'],
                'role' => $user['role'],
            ]
        ], 200);
    }

    public function show_register()
    {
        $users = User::where('role', 'user')->get();

        return response()->json([
            'data' => [
                'msg' => 'User Registrasi',
                'data' => $users,
            ]
        ], 200);
    }

    public function show_admin()
    {
        $users = User::where('role', 'admin')->get();

        return response()->json([
            'data' => [
                'msg' => 'Admin Registrasi',
                'data' => $users,
            ]
        ], 200);
    }

    public function show_register_by_id($id)
    {
        $user = User::find($id);

        return response()->json([
            'data' => [
                'msg' => 'User ID : ' . $id,
                'data' => $user
            ]
        ], 200);
    }


    public function update_register(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'nim' => 'required',
                'kelas' => 'required',
                'prodi' => 'required',
                'voting' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {

            }

            $data = $validator->validated();

            User::where('id', $id)->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'User Dengan ID : ' . $id . ' Berhasil Di Update',
                    'nama' => $user['nama'],
                    'nim' => $user['nim'],
                    'kelas' => $user['kelas'],
                    'prodi' => $user['prodi'],
                    'role' => $user['role'],
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'msg' => 'User Dengan ID : ' . $id . ' Tidak Di Temukan'
            ]
        ], 422);
    }

    public function delete_register($id)
    {
        $user = User::find($id);

        if ($user) {

            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'User Dengan ID : ' . $id . ', Berhasil Di Hapus'
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'msg' => 'User Dengan ID : ' . $id . ', Tidak Di Temukan'
            ]
        ], 422);
    }

    public function show_calons()
    {
        //done
        $calons = Calons::all();

        return response()->json($calons, 200);

    }

    public function angkatan(Request $request)
    {
        //done
        // Validasi input yang diterima dari request
        $request->validate([
            'angkatan' => 'required',
        ]);

        // Buat instance model Periode
        $angkatan = new Angkatan;
        $angkatan->angkatan = $request->input('angkatan');

        // Simpan data ke dalam tabel periode
        $angkatan->save();

        return response()->json($angkatan, 201);
    }

    public function showangkatan()
    {
        //done
        $angkatan = Angkatan::all();

        return response()->json($angkatan, 200);

    }

    public function showangkatanbyid($id)
    {
        //done
        $angkatan = Angkatan::find($id);

        if (!$angkatan) {
            return response()->json(['message' => 'Angkatan tidak ditemukan'], 404);
        }

        return response()->json($angkatan);
    }

    public function create_calons(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_ketua' => 'required|max:255',
            'foto_calon' => 'required|mimes:png,jpg,jpeg|max:2048',
            'visi' => 'required',
            'misi' => 'required',
            'suara' => 'required|int',
        ]);

        if ($validator->fails()) {

        }

        $thumbnail = $request->file('foto_calon');

        $fileName = now()->timestamp . '_' . $request->foto_calon->getClientOriginalName();

        $thumbnail->move('uploads', $fileName);

        $CalonsData = $validator->validated();

        $recipe = Calons::create([
            'nama_ketua' => $CalonsData['nama_ketua'],
            'foto_calon' => 'uploads/' . $fileName,
            'visi' => $CalonsData['visi'],
            'misi' => $CalonsData['misi'],
            'suara' => $CalonsData['suara'],


        ]);

        return response()->json([
            'data' => [
                'msg' => 'Kandidat Berhasil Di Tambahkan',
                'Kandidat' => $CalonsData['nama_ketua'],
            ]
        ]);

    }

    public function update_calons(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_ketua' => 'required|max:255',
            'foto_calon' => 'required|mimes:png,jpg,jpeg',
            'visi' => 'required',
            'misi' => 'required',
            'suara' => 'required|int',
        ]);

        if ($validator->fails()) {
        }
        $CalonsData = $validator->validated();
        $calon = Calons::find($id);

        if (isset($CalonsData['nama_ketua'])) {
            $calon->nama_ketua = $CalonsData['nama_ketua'];
            var_dump(123);
        }
//dd($validator);
//        $fileName = '';
        if (isset($CalonsData['foto_calon'])) {
            $thumbnail = $request->file('foto_calon');
            $fileName = now()->timestamp . '_' . $request->foto_calon->getClientOriginalName();
            $thumbnail->move('uploads', $fileName);
            $calon->foto_calon = 'uploads/' . $fileName;
        }
        $calon->save();

        return response()->json([
            'data' => [
                'msg' => 'Kandidat Berhasil Di Edit',
                'Kandidat' => $CalonsData['nama_ketua'],
                'visi' => $CalonsData['visi'],
                'misi' => $CalonsData['misi'],
            ]
        ], 200);
    }

    public function delete_calons($id_calon)
    {
        $user = Calons::find($id_calon);

        if ($user) {

            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Kandidat Dengan ID : ' . $id_calon . ', Berhasil Di Hapus'
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'msg' => 'Kandidat Dengan ID : ' . $id_calon . ', Tidak Di Temukan'
            ]
        ], 422);
    }

    public function pemilihan(Request $request)
    {
        //done
        //input pemilihan
        $validatedData = $request->validate([
            'nama' => 'required',
            'IdAngkatan' => 'required|exists:angkatan,IdAngkatan',
            'IdStatus' => 'required|exists:status,IdStatus',
            'deskripsi' => 'required',
        ]);

        $pemilih = ImportPemilih::create($validatedData);

        return response()->json([
            "data" => [
                'msg' => "berhasil membuat Pemilihan",
                'IdPemilih' => $pemilih['IdPemilih'],
                'nama' => $pemilih['nama'],
                'IdAngkatan' => $pemilih->angkatan->angkatan,
                'IdStatus' => $pemilih->status->status,
                'deskripsi' => $pemilih['deskripsi'],
            ],
        ], 201);
    }

    public function showpemilihan()
    {
        //done
        //tampil semua pemilihan
        $pemilih = ImportPemilih::all();

        return response()->json($pemilih, 200);
    }

    public function showpemilihanbyid($id)
    {
        //done
        //tampil pemilihab by id
        $pemilih = ImportPemilih::find($id);

        if (!$pemilih) {
            return response()->json(['error' => 'Pemilihan Tidak Ditemukan'], 404);
        }

        //dd($pemilih->status->status);
        return response()->json([
            "data" => [
                'msg' => "tampil pemilihan by id",
                'IdPemilih' => $pemilih['IdPemilih'],
                'nama' => $pemilih['nama'],
                'IdAngkatan' => $pemilih->angkatan->angkatan,
                'IdStatus' => $pemilih->status->status,
                'deskripsi' => $pemilih['deskripsi'],
            ],
        ], 201);
    }

//    public function Calons(Request $request, $IdPemilih)
//    {
//        //idpemilihan di dapatkan pada parameter request fe
//        $validator = Validator::make($request->all(), [
//            'nama_ketua' => 'required',
//            'foto_calon' => 'required|image|mimes:png,jpg,jpeg|max:2048',
//            'visi' => 'required',
//            'misi' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json(['error' => $validator->errors()], 400);
//        }
//
//        $IdUser = $request->user;
//
//        $calons = Calons::where('IdUser', $IdUser->IdUser)
//            ->where('IdPemilih', $IdPemilih)
//            ->first();
//
//        if ($calons) {
//            // User sudah terdaftar dalam tabel kandidat maka tidak bisa mengajukan lagi
//            return response()->json(['message' => 'User sudah registrasi'], 422);
//        }
//        // Upload foto_calon dan simpan nama file ke kolom foto_calon
//        $fotocalon = $request->file('foto_calon');
//        $gambarName = time().'.'.$fotocalon->extension();
//        $fotocalon->move(public_path('uploads'), $gambarName);
//
//        $pemilih = ImportPemilih::find($IdPemilih);
//
//        if (!$pemilih) {
//            return response()->json(['error' => 'Pemilihan not found'], 404);
//        }
//
//        // Simpan data calon kandidat ke database
//        $calons = new Calons;
//        $calons->IdUser = $IdUser->IdUser;
//        $calons->IdPemilih = $IdPemilih;
//        $calons->visi = $request->input('visi');
//        $calons->misi = $request->input('misi');
//        $calons->foto_calon = $gambarName;
//        $calons->save();
//
//        return response()->json(['message' => 'Data calon kandidat berhasil disimpan'], 201);
//    }


}
