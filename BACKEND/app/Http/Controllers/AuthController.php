<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            var_dump($validator->fails());
        }

        if (Auth::attempt($validator->validated())) {

            $payload = [
                'nim' => Auth::user()->nim,
                'role' => Auth::user()->role,
                'nama' => Auth::user()->nama,
                'prodi' => Auth::user()->prodi,
                'kelas' => Auth::user()->kelas,
                'iat' => now()->timestamp,
                'exp' => now()->timestamp + 7200,
            ];

            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            return response()->json([
                'data' => [
                    'msg' => 'Berhasil Login',
                    'nim' => Auth::user()->nim,
                    'role' => Auth::user()->role,
                    'nama' => Auth::user()->nama,
                    'prodi' => Auth::user()->prodi,
                    'kelas' => Auth::user()->kelas,
                ],
                'token' => 'Bearer ' . $token
            ], 200);
        }

        return response()->json('Email Atau Password Salah', 422);
    }
}
