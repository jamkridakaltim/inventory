<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==============================
        // ADMIN
        // ==============================

        User::updateOrCreate(
            ['email' => 'admin@simas.com'],
            [
                'role_id' => 1,
                'full_name' => 'Administrator',
                'username' => 'admin',
                'password_hash' => bcrypt('123456'),
                'is_active' => true,
            ]
        );


        // ==============================
        // STAFF
        // ==============================

        User::updateOrCreate(
            ['email' => 'staff@simas.com'],
            [
                'role_id' => 2,
                'full_name' => 'Staff SIMAS',
                'username' => 'staff',
                'password_hash' => bcrypt('123456'),
                'is_active' => true,
            ]
        );


        // ==============================
        // DIREKTUR
        // ==============================

        User::updateOrCreate(
            ['email' => 'direktur@simas.com'],
            [
                'role_id' => 3,
                'full_name' => 'Direktur SIMAS',
                'username' => 'direktur',
                'password_hash' => bcrypt('123456'),
                'is_active' => true,
            ]
        );
    }
}