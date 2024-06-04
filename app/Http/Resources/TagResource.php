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
        $name = 'hola';
        switch ($this->type) {
            case 'game':
                $game = Game::find($this->tag_id);
                $name = $game->name;
                break;
            case 'genre':
                $genre = Genre::find($this->tag_id);
                $name = $genre->name;
                break;
            case 'platform':
                $platform = Platform::find($this->tag_id);
                $name = $platform->name;
                break;
            case 'developer':
                $developer = Developer::find($this->tag_id);
                $name = $developer->name;
                break;
        }

        return [
            'type' => $this->type,
            'tag_id' => $this->tag_id,
            'name' => $name
        ];;
    }
}
