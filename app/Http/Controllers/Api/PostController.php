<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::where('status', 'published')
                        ->with('user')
                        ->orderByDesc('created_at')
                        ->paginate(10);
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with(['user', 'comments.user'])
                        ->where('status', 'published')
                        ->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found or not published.'], 404);
        }
        return new PostResource($post);
    }

    public function comments($id)
    {
        $post = Post::where('status', 'published')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found or not published.'], 404);
        }

        $comments = $post->comments()
                         ->with('user')
                         ->latest()
                         ->paginate(15);
        return CommentResource::collection($comments);
    }
}