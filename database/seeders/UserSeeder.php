<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Mengisi akun IT Support demo.
     * updateOrCreate dipakai agar seeder aman dijalankan ulang tanpa membuat email dobel.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'it@helpdesk.com'],
            [
                'name' => 'IT Support',
                'password' => Hash::make('itpass123'),
                'role' => 'it',
            ]
        );
    }
}
