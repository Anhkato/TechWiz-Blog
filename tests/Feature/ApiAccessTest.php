<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum; // Thêm Sanctum
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class ApiAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_list_of_published_posts_via_api_without_token()
    {
        Post::factory()->count(3)->create(['status' => 'published']);
        Post::factory()->count(2)->create(['status' => 'draft']);

        $response = $this->getJson(route('api.posts.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data'); // Chỉ lấy bài published
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'slug', 'content_excerpt', 'status', 'author', 'created_at', 'updated_at']
            ],
            'links',
            'meta'
        ]);
    }

    /** @test */
    public function can_get_a_single_published_post_via_api_without_token()
    {
        $post = Post::factory()->create(['status' => 'published']);

        $response = $this->getJson(route('api.posts.show', $post->id));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $post->id, 'title' => $post->title]);
    }

    /** @test */
    public function cannot_create_comment_via_api_without_token()
    {
        $post = Post::factory()->create(['status' => 'published']);
        $response = $this->postJson(route('api.comments.store', $post->id), [
            'body' => 'Attempting to comment without token.'
        ]);
        $response->assertUnauthorized(); // Hoặc 401
    }

    /** @test */
    public function authenticated_user_can_create_comment_via_api_with_token()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['status' => 'published']);

        Sanctum::actingAs(
            $user,
            ['*'] // abilities, '*' cho phép tất cả, hoặc chỉ định cụ thể nếu có
        );

        $commentData = ['body' => 'My API comment.'];
        $response = $this->postJson(route('api.comments.store', $post->id), $commentData);

        $response->assertCreated(); // Status 201
        $response->assertJsonFragment(['body' => 'My API comment.']);
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'My API comment.'
        ]);
    }
}