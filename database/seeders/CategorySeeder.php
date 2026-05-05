<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Mengisi data kategori awal untuk pilihan saat user membuat tiket.
     * updateOrInsert dipakai agar data tidak dobel saat seeder dijalankan ulang.
     */
    public function run(): void
    {
        foreach (['Hardware', 'Software', 'Network'] as $name) {
            DB::table('categories')->updateOrInsert(
                ['name' => $name],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
