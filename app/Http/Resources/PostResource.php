<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content_excerpt' => Str::limit(strip_tags($this->content), 150),
            'content' => $this->when($request->routeIs('api.posts.show'), $this->content),
            'status' => $this->status,
            'author' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'comments_count' => $this->whenCounted('comments'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}