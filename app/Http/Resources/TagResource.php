<?php

namespace App\Http\Resources;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        switch ($this->type) {
            case 'game':
                $object = Game::find($this->tag_id);
                break;
            case 'genre':
                $object = Genre::find($this->tag_id);
                break;
            case 'platform':
                $object = Platform::find($this->tag_id);
                break;
            case 'developer':
                $object = Developer::find($this->tag_id);
                break;
        }

        return [
            'type' => $this->type,
            'tag_id' => $this->tag_id,
            'slug' => $object->slug,
            'name' => $object->name
        ];;
    }
}
