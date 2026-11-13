<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id');

        $posts = Post::where('is_published', true)->where('is_pinned', false)->get();

        foreach ($posts as $post) {
            Comment::factory()
                ->count(rand(0, 5))
                ->state(fn () => ['user_id' => $userIds->random()])
                ->create(['post_id' => $post->id]);
        }

        $pinned = Post::where('is_pinned', true)->firstOrFail();
        $lola = User::where('email', 'lola.rabanes@bulletin.test')->firstOrFail();
        $kagawad = User::where('email', 'kagawad.rabanes@bulletin.test')->firstOrFail();
        $resident = User::whereNotIn('id', [$lola->id, $kagawad->id])->inRandomOrder()->firstOrFail();

        $pinned->comments()->create([
            'user_id' => $resident->id,
            'body' => 'Asa mi mo-meet Kagawad? Sa atubangan ba sa hall?',
        ]);
        $pinned->comments()->create([
            'user_id' => $kagawad->id,
            'body' => 'Oo, sa atubangan sa barangay hall, 7:00 AM. Dala mog gloves.',
        ]);
        $pinned->comments()->create([
            'user_id' => $lola->id,
            'body' => 'Mo-apil ko! Magdala ko og bibingka para sa tanan.',
        ]);
    }
}
