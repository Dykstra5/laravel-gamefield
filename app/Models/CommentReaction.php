<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'comment_id',
        'type',
        'user_id'
    ];
}
