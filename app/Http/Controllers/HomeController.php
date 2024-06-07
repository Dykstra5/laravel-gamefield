<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\UserResource;
use App\Models\Developer;
use App\Models\FavouriteTag;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $postsQuery = Post::postsForTimeline(Auth::id(), true);
        $posts = $postsQuery->paginate(10);
        $posts = PostResource::collection($posts);

        if ($request->wantsJson()) {
            return $posts;
        }

        $following = User::query()
            ->select('users.*')
            ->join('followers', 'user_id', 'users.id')
            ->where('follower_id', Auth::id())
            ->get();

        $tags = FavouriteTag::where('user_id', Auth::id())->get();

        $modelMap = [
            'game' => Game::class,
            'genre' => Genre::class,
            'developer' => Developer::class,
            'platform' => Platform::class,
        ];

        $tagsFollowing = [];

        foreach ($tags as $tag) {
            $modelClass = $modelMap[$tag->type];
            $tagObject = $modelClass::findOrFail($tag->tag_id);

            $tagObject->type = $tag->type;
            $tagObject->tag_id = $tag->tag_id;

            $tagsFollowing[] = $tagObject;
        }

        return Inertia::render('Home', [
            'posts' => $posts,
            'usersFollowing' => UserResource::collection($following),
            'tagsFollowing' => TagResource::collection($tagsFollowing),
        ]);
    }
}
