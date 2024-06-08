<?php

namespace App\Http\Resources;

use App\Models\PostReaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = Auth::id();
        $hasLiked = $this->reactions->contains('user_id', $userId);

        return [
            'post_id' => $this->id,
            'title' => $this->title,
            'content' => $this->body,
            'created_at' => Carbon::parse($this->created_at)
                ->setTimezone('Europe/Madrid')
                ->format('d M. Y h:i a'),
            'updated_at' => Carbon::parse($this->updated_at)
                ->setTimezone('Europe/Madrid')
                ->format('d M. Y h:i a'),
            'user' => new UserResource($this->user),
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'likes' => $this->reactions_count,
            'has_liked' => $hasLiked,
            'comments' => $this->comments_count,
            'last_5_comments' => CommentResource::collection($this->latest5Comments),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
