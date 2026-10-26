<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::firstOrCreate([
            'id' => (string) Str::uuid(),
            'nama_cabang' => 'Cabang Utama',
            'alamat' => 'Jakarta',
            'is_active' => true,
        ]);

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'id' => (string) Str::uuid(),
                'branch_id' => $branch->id,
                'nik' => 'ADM001',
                'nama' => 'Admin Utama',
                'jabatan' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'foto_url' => null,
                'face_registered' => false,
                'is_active' => true,
            ]
        );
    }
}
