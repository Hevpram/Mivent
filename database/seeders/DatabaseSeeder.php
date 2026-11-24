<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** php artisan migrate:fresh --seed */
    public function run(): void
    {
        $this->call([
            ReferenceSeeder::class, // jurusan, organisasi, kategori, skp
            UserSeeder::class,      // akun demo tiap role
            EventSeeder::class,     // contoh event + pendaftaran
        ]);
    }
}
