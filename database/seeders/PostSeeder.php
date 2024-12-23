<?php

namespace Database\Seeders;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Post::query()->update([
//            'created_at' => Post::raw('DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 365) DAY)'),
//            'updated_at' => Post::raw('LEAST(DATE_ADD(created_at, INTERVAL FLOOR(RAND() * 30) DAY), NOW())')
//        ]);

        Post::chunk(100, function ($posts) {
            foreach ($posts as $post) {
                $createdAt = Carbon::now()->subDays(rand(1, 365));
                $updatedAt = $createdAt->copy()->addDays(rand(0, 30));

                if ($updatedAt->isFuture()) {
                    $updatedAt = Carbon::now();
                }

                $post->update([
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt
                ]);
            }
        });

    }
}
