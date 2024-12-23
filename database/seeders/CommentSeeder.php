<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $excludedPostIds = [70];
        $faker = Faker::create();
        for ($i = 0; $i < 100; $i++) {
            $randomPost = Post::whereNotIn('id', $excludedPostIds)
                ->whereBetween('id', [51, 202])
                ->inRandomOrder()
                ->first();
            Comment::create([
                'user_id' => rand(1, 35),
                'post_id' => $randomPost?$randomPost->id:null,
                'body' => $faker->sentence,
            ]);
        }
    }
}
