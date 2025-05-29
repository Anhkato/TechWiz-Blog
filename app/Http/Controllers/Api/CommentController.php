<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::where('status', 'published')->find($postId);
        if (!$post) {
            return response()->json(['message' => 'Post not found or not published.'], 404);
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
        ]);

        if ($post->user_id !== Auth::id() && $post->user) {
             $post->user->notify(new NewCommentNotification($comment));
        }

        return (new CommentResource($comment->load('user')))
                ->additional(['message' => 'Comment posted successfully.'])
                ->response()
                ->setStatusCode(201);
    }
}