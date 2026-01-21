<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'is_active' => '1',
            'role' => '2', // Super Admin
            'nama' => 'Super Admin',
            'email' => 'admin@nayakaerahusada.com',
            'password' => bcrypt('password'), // Password is hashed
        ]);
    }
}
