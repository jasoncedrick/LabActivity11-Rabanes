<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Lab11Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed();
    }

    public function test_seeded_relationships(): void
    {
        $kagawad = User::where('email', 'kagawad.rabanes@bulletin.test')->sole();
        $this->assertSame('Kagawad Jason Cedrick Rabanes', $kagawad->author->name);
        $this->assertDatabaseCount('users', 10);
        $this->assertDatabaseCount('tags', 5);

        $pinned = Post::where('is_pinned', true)->sole();
        $this->assertEqualsCanonicalizing(['Purok Rabanes', 'Urgent'], $pinned->tags->pluck('name')->all());
        $this->assertGreaterThanOrEqual(3, $pinned->comments()->count());
        $this->assertTrue($pinned->comments()->whereRelation('user', 'name', 'Lola Rabanes')->exists());

        foreach (Post::withCount('tags')->get() as $post) {
            $this->assertTrue($post->tags_count >= 1 && $post->tags_count <= 3);
        }
    }

    public function test_pages_load_without_lazy_loading(): void
    {
        $pinned = Post::where('is_pinned', true)->sole();

        $this->get('/posts')->assertOk()->assertSee('#Purok Rabanes');
        $this->get('/posts?tag=Urgent')->assertOk()->assertViewHas('posts', function ($posts) {
            return $posts->every(fn ($post) => $post->tags->contains('name', 'Urgent'));
        });
        $this->get('/posts/'.$pinned->id)->assertOk()->assertSee('Lola Rabanes')->assertSee('Sign in to comment');
        $this->get('/authors/'.$pinned->author_id)->assertOk()->assertSee('comments received');
        $this->get('/login')->assertOk();
    }

    public function test_comment_validation_and_create(): void
    {
        $lola = User::where('email', 'lola.rabanes@bulletin.test')->sole();
        $post = Post::where('is_pinned', true)->sole();

        $this->post('/posts/'.$post->id.'/comments', ['body' => 'Hello'])->assertRedirect('/login');

        $this->actingAs($lola)
            ->from('/posts/'.$post->id)
            ->post('/posts/'.$post->id.'/comments', ['body' => 'Hi'])
            ->assertSessionHasErrors(['body' => 'Purok Rabanes comments need at least 3 characters.']);

        $this->actingAs($lola)
            ->post('/posts/'.$post->id.'/comments', ['body' => 'Salamat Kagawad!'])
            ->assertRedirect('/posts/'.$post->id)
            ->assertSessionHas('status', 'Comment posted.');
        $this->assertDatabaseHas('comments', ['post_id' => $post->id, 'user_id' => $lola->id, 'body' => 'Salamat Kagawad!']);

        $draft = Post::where('is_published', false)->first();
        $this->actingAs($lola)->post('/posts/'.$draft->id.'/comments', ['body' => 'Hello draft'])->assertNotFound();
    }

    public function test_old_input_and_tag_errors(): void
    {
        $kagawad = User::where('email', 'kagawad.rabanes@bulletin.test')->sole();
        $libre = Tag::where('name', 'Libre')->value('id');

        $this->actingAs($kagawad)->from('/posts/create')
            ->post('/posts', ['title' => 'Ayuda', 'category' => 'Peace & Order', 'tags' => [$libre, 9999]])
            ->assertRedirect('/posts/create')
            ->assertSessionHasErrors(['body', 'tags.1' => 'One of the selected tags does not exist.']);

        $this->actingAs($kagawad)->withSession(['_old_input' => ['title' => 'Ayuda', 'tags' => [(string) $libre]]])
            ->get('/posts/create')
            ->assertOk()
            ->assertSee('value="Ayuda"', false)
            ->assertSee('value="'.$libre.'" checked', false);

        $this->actingAs($kagawad)->withSession(['_old_input' => ['tags' => 'oops']])
            ->get('/posts/create')->assertOk();
    }

    public function test_only_authors_can_post(): void
    {
        $lola = User::where('email', 'lola.rabanes@bulletin.test')->sole();
        $kagawad = User::where('email', 'kagawad.rabanes@bulletin.test')->sole();
        $tagIds = Tag::whereIn('name', ['Libre', 'Schedule'])->pluck('id')->all();

        $data = [
            'title' => 'Libreng bakuna sa Sabado',
            'body' => 'Libreng bakuna para sa mga bata sa barangay hall, 8 AM hangtod 12 NN.',
            'category' => 'Health',
            'tags' => $tagIds,
            'is_published' => '1',
        ];

        $this->actingAs($lola)->get('/posts/create')->assertForbidden();
        $this->actingAs($lola)->post('/posts', $data)->assertForbidden();

        $this->actingAs($kagawad)->get('/posts/create')->assertOk();
        $this->actingAs($kagawad)->post('/posts', ['tags' => []])
            ->assertSessionHasErrors(['title', 'body', 'category', 'tags']);

        $this->actingAs($kagawad)->post('/posts', $data)->assertRedirect();
        $post = Post::where('title', 'Libreng bakuna sa Sabado')->sole();
        $this->assertSame(Author::where('user_id', $kagawad->id)->value('id'), $post->author_id);
        $this->assertNotNull($post->published_at);
        $this->assertEqualsCanonicalizing($tagIds, $post->tags->pluck('id')->all());
    }
}
