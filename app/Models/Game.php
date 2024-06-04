<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'image_background',
        'released',
        'metacritic',
        'playtime',
    ];
}