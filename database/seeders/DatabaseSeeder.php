<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Data Roles
        DB::table('roles')->insert([
            ['nama_role' => 'petugas'],
        ]);

        // Data Area
        DB::table('area')->insert([
            ['nama_area' => 'Bekasi'],
            ['nama_area' => 'Pontianak'],
        ]);
    }
}