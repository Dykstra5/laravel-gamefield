<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "username" => $this->username,
            "cover_src" => $this->cover_path ? Storage::url($this->cover_path) : '',
            "avatar_src" => $this->avatar_path ? Storage::url($this->avatar_path) : '',
            "isAdmin" => $this->isAdmin(),
        ];
    }

    protected function isAdmin()
    {
        return $this->role_id === 1;
    }

}
