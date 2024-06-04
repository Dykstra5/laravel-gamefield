<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'user' => new UserResource($this->user),
            'created_at' => Carbon::parse($this->created_at)
                ->setTimezone('Europe/Madrid')
                ->format('d M. Y h:i a'),
            'likes' => $this->comment_reactions_count,
            'has_liked' => $this->commentReactions->count() > 0,
        ];
    }
}
