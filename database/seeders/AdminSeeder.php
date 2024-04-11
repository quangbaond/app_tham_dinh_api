<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Admin',
            'phone' => '0123456789',
            'password' => bcrypt('123456'),
            'role' => 2,
            'status' => 1,
            'email' => 'admin@gmail.com',
            'phone_verified_at' => now(),
        ]);
    }
}
