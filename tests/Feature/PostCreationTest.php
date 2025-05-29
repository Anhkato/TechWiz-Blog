<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use PHPUnit\Framework\Attributes\Test;

class PostCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_a_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user); 

        $postData = [
            'title' => 'My First Test Post',
            'content' => 'This is the content of my first test post.',
            'status' => 'published',
        ];

        $response = $this->post(route('posts.store'), $postData);

        $response->assertRedirect(route('dashboard')); 
        $this->assertDatabaseHas('posts', [
            'title' => 'My First Test Post',
            'user_id' => $user->id,
            'status' => 'published'
        ]);

        $post = Post::where('title', 'My First Test Post')->first();
        $this->assertNotNull($post->slug);
        $this->assertEquals('my-first-test-post', $post->slug); 
    }

    /** @test */
    public function guest_cannot_create_a_post()
    {
        $postData = [
            'title' => 'Guest Post',
            'content' => 'Guest content.',
            'status' => 'published',
        ];
        $this->post(route('posts.store'), $postData)
             ->assertRedirect(route('login')); // Hoặc 403 nếu không có redirect
    }

    /** @test */
    public function creating_a_post_requires_title_and_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('posts.store'), [])
             ->assertSessionHasErrors(['title', 'content']);
    }
}