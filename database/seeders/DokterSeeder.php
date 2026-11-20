<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $namaDokter = [
            'dr. Ratna Wijayanti, Sp.S',
            'dr. Aditya Iqbal M, Sp.OG',
            'dr. Bagus Fitriadi, Sp.JP',
            'dr. Devi Anggraeni P, Sp.KJ',
            'Dr. Spesialis Radiologi',
        ];

        foreach ($namaDokter as $nama) {
            DB::table('user')->updateOrInsert(
                ['username' => $nama],
                [
                    'password' => Hash::make('dokter@123456'),
                    'role'     => 'dokter',
                ]
            );
        }
    }
}