<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@eduworld.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
