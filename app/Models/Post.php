<?php

namespace App\Models;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tag;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'deleted_by'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function latest5Comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function tags(): HasMany
    {
        return $this->hasMany(PostTag::class);
    }

    public static function postsForTimeline($userId, $getLatest = true): Builder
    {
        $query = Post::query() // SELECT * FROM posts
            ->withCount('reactions') // SELECT COUNT(*) from reactions
            ->withCount('comments') // SELECT COUNT(*) from reactions
            ->with([
                'user',
                'attachments',
                'latest5Comments' => function ($query) {
                    $query->withCount('commentReactions'); // SELECT * FROM comments WHERE post_id IN (1, 2, 3...)
                    // SELECT COUNT(*) from reactions
                },
                'latest5Comments.user',
                'latest5Comments.commentReactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId); // SELECT * from reactions WHERE user_id = ?
                },
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ]);
        if ($getLatest) {
            $query->latest();
        }

        return $query;
    }

    public static function singlePost($userId, Post $post, $getLatest = true): Builder
    {
        $query = Post::query() // SELECT * FROM posts
            ->withCount('reactions') // SELECT COUNT(*) from reactions
            ->withCount('comments') // SELECT COUNT(*) from reactions
            ->with([
                'user',
                'attachments',
                'latest5Comments' => function ($query) {
                    $query->withCount('commentReactions'); // SELECT * FROM comments WHERE post_id IN (1, 2, 3...)
                    // SELECT COUNT(*) from reactions
                },
                'latest5Comments.user',
                'latest5Comments.commentReactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId); // SELECT * from reactions WHERE user_id = ?
                },
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ]);
        if ($getLatest) {
            $query->latest();
        }

        return $query;
    }
}
