<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index(Request $request, $type, $slug)
    {
        try {
            $modelMap = [
                'game' => Game::class,
                'genre' => Genre::class,
                'developer' => Developer::class,
                'platform' => Platform::class,
            ];

            if (!array_key_exists($type, $modelMap)) {
                abort(404, 'Invalid type');
            }

            $modelClass = $modelMap[$type];

            $tag = $modelClass::where('slug', $slug)->firstOrFail();

            $postTagIds = PostTag::where('type', $type)
                ->where('tag_id', $tag->id)
                ->pluck('post_id');

            $postsQuery = Post::postsForTimeline(Auth::id(), true)
                ->whereIn('id', $postTagIds);

            $posts = $postsQuery->paginate(10);
            $posts = PostResource::collection($posts);

            return Inertia::render('Tag/View', [
                'posts' => $posts,
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dashboard');
        }
    }
}
