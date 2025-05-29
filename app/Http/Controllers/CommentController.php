<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ], [], ['body' => 'comment content']);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->input('body'),
        ]);

        if ($post->user_id !== Auth::id()) {
            $post->user->notify(new NewCommentNotification($comment));
        }

        return back()->with('success', 'Comment posted successfully!');
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        if ($user->email === 'admin@gmail.com') {
             $comment->delete();
             return back()->with('success', 'xóa thành công.');
        }
       
        $comment->delete();
        return back()->with('success', 'xóa thành công.');
    }
}