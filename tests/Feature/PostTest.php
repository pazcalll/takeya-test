<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
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

    public function test_access_posts_page_when_no_content_available(): void
    {
        Post::query()->delete();
        $asserts = function ($response, $user = null) {
            $response->assertStatus(200);
            $response->assertSee('No posts found.');
            $response->assertSee($user ? $user->name : 'Guest');
        };

        $response = $this
            ->get('/posts');
        $asserts($response);

        $response = $this
            ->actingAs($this->user)
            ->get('/posts');
        $asserts($response, $this->user);
    }

    public function test_access_posts_detail_page(): void
    {
        $post = Post::query()
            ->isNotDraft()
            ->published()
            ->first();

        $asserts = function ($response, $post, $user = null) {
            $response->assertStatus(200);
            $response->assertSee($post->title);
            $response->assertSee($post->content);
            $response->assertSee($user ? $user->name : 'Guest');
        };

        $response = $this
            ->get(route('posts.show', $post->id));
        $asserts($response, $post);

        $response = $this
            ->actingAs($this->user)
            ->get(route('posts.show', $post->id));
        $asserts($response, $post, $this->user);
    }

    public function test_access_posts_page_with_pagination()
    {
        $pageAsserts = function ($response, $user = null) {
            $response->assertStatus(200);
            $response->assertSee($user ? $user->name : 'Guest');
            $response->assertSee('All Posts');
        };
        $dataAsserts = function ($response, $posts) {
            $response->assertSee('Pagination Navigation');
            foreach ($posts as $post) {
                $response->assertSee($post->title);
                $response->assertSee($post->user->name);
                $response->assertSee($post->created_at->format('Y-m-d'));
            }
        };

        $posts = Post::query()
            ->orderByDesc('created_at')
            ->isNotDraft()
            ->published()
            ->take(10);

        $response = $this
            ->get('/posts');

        $pageAsserts($response);
        $dataAsserts($response, $posts);

        $response = $this
            ->actingAs($this->user)
            ->get('/posts');
        $pageAsserts($response, $this->user);
        $dataAsserts($response, $posts);
    }

    public function test_not_found_when_accessing_draft_post(): void
    {
        $post = Post::query()
            ->isDraft()
            ->first();

        $response = $this
            ->get(route('posts.show', $post->id));

        $response->assertSee('Not Found');
    }

    public function test_unauthorized_when_accessing_other_user_edit_page(): void
    {
        $post = Post::query()
            ->isNotDraft()
            ->published()
            ->whereDoesntHave('user', fn ($query) => $query->where('id', $this->user->id))
            ->first();

        $response = $this
            ->actingAs($this->user)
            ->get(route('posts.edit', $post->id));

        $response->assertSee('Forbidden');
    }

    public function test_redirect_to_login_when_accessing_create_page_as_guest(): void
    {
        $response = $this
            ->get(route('posts.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
