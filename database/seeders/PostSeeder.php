<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $authors = Author::all();
        $categories = ['Announcement', 'Health', 'Peace & Order', 'Ayuda', 'Education'];
        Post::factory()->count(40)
            ->sequence(fn ($sequence) => [
                'author_id' => $authors->random()->id,

                'category' => $categories[$sequence->index % count($categories)],
            ])->create();
        Post::factory()->count(10)->draft()
            ->sequence(fn ($sequence) => ['author_id' => $authors->random()->id])
            ->create();
        $kagawad = Author::where('email', 'kagawad.rabanes@bulletin.test')->firstOrFail();
        Post::create([
            'author_id' => $kagawad->id,
            'title' => 'Purok Rabanes Clean-up Drive',
            'category' => 'Announcement',
            'body' => "Join our Purok Rabanes clean-up drive at Barangay Matina Crossing on November 23, 2026. Meet outside the barangay hall at 7:00 AM.\n\nBring gloves, a broom, and drinking water. We will clean the pathways and collect litter in designated areas.\n\nPlease coordinate with Kagawad Jason Cedrick Rabanes at the assembly area. Everyone is welcome!",
            'is_published' => true,
            'is_pinned' => true,

            'published_at' => '2026-11-23 07:00:00',
            'views' => 0,
        ]);
    }
}
