<?php
namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function create(User $user): bool
    {
        return true;
    }
      public function before(User $user, string $ability)
    {
        if ($user->email === 'admin@gmail.com') {
            return true;
        }
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id|| $user->mail === 'admin@gmail.com';
        
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id|| $user->mail === 'admin@gmail.com';
    }
}