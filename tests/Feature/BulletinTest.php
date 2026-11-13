<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulletinTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_seed_counts_and_personal_post(): void
    {
        $this->seed();
        $this->assertDatabaseCount('authors', 5);
        $this->assertDatabaseCount('posts', 51);
        $this->assertSame(10, Post::where('is_published', false)->count());
        $pinned = Post::where('is_pinned', true)->sole();
        $this->assertSame('2026-11-23', $pinned->published_at->format('Y-m-d'));
        $this->assertSame(Author::where('email', 'kagawad.rabanes@bulletin.test')->sole()->id, $pinned->author_id);
        $this->get('/posts')->assertOk()->assertViewHas('posts', function ($posts) use ($pinned) {
            return $posts->perPage() === 7 && $posts->total() === 41 && $posts->first()->id === $pinned->id;
        });
    }

    public function test_filters_and_pagination_preserve_query(): void
    {
        $author = Author::factory()->create();
        Post::factory()->count(8)->create(['author_id' => $author->id, 'title' => 'School notice', 'category' => 'Education']);
        Post::factory()->create(['author_id' => $author->id, 'title' => 'School health', 'category' => 'Health']);
        Post::factory()->draft()->create(['author_id' => $author->id, 'title' => 'School draft', 'category' => 'Education']);
        $this->get('/posts?q=School&category=Education')->assertOk()->assertViewHas('posts', function ($posts) {
            return $posts->total() === 8 && $posts->count() === 7
                && str_contains($posts->nextPageUrl(), 'q=School')
                && str_contains($posts->nextPageUrl(), 'category=Education');
        });
        $this->get('/posts?q=School&category=Education&page=2')->assertOk()->assertViewHas('posts', fn ($posts) => $posts->count() === 1);
        $this->get('/posts?q=does-not-exist')->assertOk()->assertSee('No posts match your search.')->assertSee('Showing 0–0 of 0 posts');
    }

    public function test_show_increments_views_but_drafts_and_missing_posts_are_hidden(): void
    {
        $post = Post::factory()->create(['views' => 2]);
        $draft = Post::factory()->draft()->create(['views' => 0]);
        $this->get('/posts/'.$post->id)->assertOk()->assertSee('3 views');
        $this->assertSame(3, $post->fresh()->views);
        $this->get('/posts/'.$draft->id)->assertNotFound();
        $this->assertSame(0, $draft->fresh()->views);
        $this->get('/posts/999999')->assertNotFound();
    }
}
