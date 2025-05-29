<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();

        for ($i = 1; $i <= 20; $i++) {
            Post::create([
                'user_id' => $user->id,
                'title' => "Sample Post Title {$i}",
                'slug' => Str::slug("Sample Post Title {$i}"),
                'content' => "This is the sample content for post number {$i}. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                'status' => 'published', 
                'created_at' => now()->subDays(20 - $i),
                'updated_at' => now()->subDays(20 - $i),
            ]);
        }
    }
}
