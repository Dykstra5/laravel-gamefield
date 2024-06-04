<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'image_background'
    ];
}
