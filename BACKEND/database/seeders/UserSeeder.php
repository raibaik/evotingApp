<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama' => 'Daffa Rifqi Abyansyah',
            'nim' => '21416255201090',
            'kelas' => 'IF21D',
            'prodi' => 'Teknik Informatika',
            'voting' => '0',
            'password' => '12345',
            'role' => 'admin',
        ]);
    }
}
