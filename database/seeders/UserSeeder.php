<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Lola Rabanes',
            'email' => 'lola.rabanes@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $kagawadUser = User::create([
            'name' => 'Kagawad Jason Cedrick Rabanes',
            'email' => 'kagawad.rabanes@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $kagawad = Author::where('email', 'kagawad.rabanes@bulletin.test')->firstOrFail();
        $kagawad->user_id = $kagawadUser->id;
        $kagawad->save();

        User::factory()->count(8)->create();
    }
}
