<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        Author::create([
            'name' => 'Kagawad Jason Cedrick Rabanes',
            'position' => 'Kagawad',
            'email' => 'kagawad.rabanes@bulletin.test',
        ]);
        Author::factory()->count(4)->create();
    }
}
