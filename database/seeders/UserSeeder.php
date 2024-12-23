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
        for($i = 18; $i <= 34; $i++) {
            User::create([
                'id' => $i,
                'username' => 'user'. $i,
                'email' => 'user'. $i. '@example.com',
                'password' => bcrypt('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
                'is_admin' => 0
            ]);
        }
    }
}
