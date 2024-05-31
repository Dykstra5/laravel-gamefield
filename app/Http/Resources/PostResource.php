<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
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
            'group' => $this->group,
            'attachments' => $this->attachments,
        ];
    }
}
