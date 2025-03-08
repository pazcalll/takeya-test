<?php

namespace Database\Seeders;

use App\Models\Post;
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
        //
        User::factory()
            ->has(
                Post::factory()
                    ->state([
                        'is_draft' => true,
                        'publish_date' => now()->subDay(),
                    ]),
                'posts'
            )
            ->has(
                Post::factory()
                    ->state([
                        'is_draft' => false,
                        'publish_date' => now()->addDay(),
                    ]),
                'posts'
            )
            ->has(
                Post::factory()
                    ->state([
                        'is_draft' => true,
                        'publish_date' => null,
                    ]),
                'posts'
            )
            ->has(
                Post::factory()
                    ->state([
                        'is_draft' => true,
                        'publish_date' => now()->addDay(),
                    ]),
                'posts'
            )
            ->has(
                Post::factory()
                    ->state([
                        'is_draft' => false,
                        'publish_date' => now()->subDay(),
                    ]),
                'posts'
            )
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        User::factory()
            ->count(17)
            ->has(Post::factory()->count(7))
            ->create();
    }
}
