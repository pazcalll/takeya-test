<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_access_posts_page(): void
    {
        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('All Posts');
    }

    public function test_access_posts_page_when_no_content_available(): void
    {
        $response = $this
            ->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('No posts found.');
    }

    public function test_access_posts_page_when_content_exist(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/posts');

        $response->assertStatus(200);
        $response->assertSee(route('posts.show', $post->id));

        $response = $this
            ->get('/posts');

        $response->assertStatus(200);
        $response->assertSee(route('posts.show', $post->id));
    }

    public function test_access_post_detai_page(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('posts.show', $post->id));

        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee($post->content);
    }
}
