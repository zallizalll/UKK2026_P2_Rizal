<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'     => 'Admin Utama',
                'email'    => 'admin@ukk2026.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'status'   => 'aktif',
            ],
            [
                'name'     => 'Petugas Satu',
                'email'    => 'petugas@ukk2026.com',
                'password' => Hash::make('petugas123'),
                'role'     => 'petugas',
                'status'   => 'aktif',
            ],
            [
                'name'     => 'Owner Boss',
                'email'    => 'owner@ukk2026.com',
                'password' => Hash::make('owner123'),
                'role'     => 'owner',
                'status'   => 'aktif',
            ],
        ]);
    }
}