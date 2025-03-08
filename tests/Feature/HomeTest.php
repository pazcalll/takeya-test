<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::with('posts')->first();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_access_home_page_as_guest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Please');
        $response->assertSee('login');
        $response->assertSee('or');
        $response->assertSee('register');

        $response = $this
            ->actingAs($this->user)
            ->get('/');

        $response->assertStatus(200);
        if ($this->user->posts->isEmpty()) {
            $response->assertSee('You have not created any posts yet.');
        } else {
            $response->assertDontSee('You have not created any posts yet.');
        }
    }

    public function test_create_posts_button(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Create New Post');

        $response = $this
            ->actingAs($this->user)
            ->get('/');

        $response->assertStatus(200);
        $response->assertSee('Create New Post');
    }
}
