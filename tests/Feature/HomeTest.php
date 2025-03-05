<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_access_home_page_as_guest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Please');
        $response->assertSee('login');
        $response->assertSee('or');
        $response->assertSee('register');
    }

    public function test_access_home_page_as_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertStatus(200);
        $response->assertSee('You have not created any posts yet.');
    }

    public function test_access_home_page_as_authenticated_user_with_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('posts.show', $post->id));
    }
}
