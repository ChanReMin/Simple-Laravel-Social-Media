<?php

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $totalUsers = count($users);
        $existingRelations = [];

        for ($i = 0; $i < 100; $i++) {
            $userId = $users[array_rand($users)];
            $followedUserId = $users[array_rand($users)];

            while ($userId === $followedUserId || in_array("$userId-$followedUserId", $existingRelations)) {
                $followedUserId = $users[array_rand($users)];
            }

            Follow::create([
                'user_id' => $userId,
                'followed_user' => $followedUserId
            ]);

            $existingRelations[] = "$userId-$followedUserId";
        }
    }
}
