<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'image_background' => $this->image_background,
            'released' => Carbon::parse($this->released)
                ->format('d-M-Y'),
            'metacritic' => $this->metacritic,
            'playtime' => $this->playtime . 'h',
        ];
    }
}
