<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Http\Resources\UserResource;
use App\Models\Developer;
use App\Models\FavouriteTag;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function searchGlobal($keyword)
    {
        if (empty($keyword)) {
            return redirect()->route('dashboard');
        }

        $users = User::query()->where('name', 'like', "%$keyword%")
            ->orWhere('username', 'like', "%$keyword%")
            ->get();
        $genres = Genre::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();
        $games = Game::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();
        $platforms = Platform::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();
        $developers = Developer::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();

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

        return Inertia::render('Search/View', [
            'users' => UserResource::collection($users),
            'genres' => $genres,
            'games' => $games,
            'platforms' => $platforms,
            'developers' => $developers,
            'usersFollowing' => UserResource::collection($following),
            'tagsFollowing' => TagResource::collection($tagsFollowing),
        ]);
    }

    public function searchTags($keyword)
    {
        if (empty($keyword)) {
            return response()->json([]);
        }

        $genres = Genre::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();
        $games = Game::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();
        $platforms = Platform::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();
        $developers = Developer::query()->where('name', 'like', "%$keyword%")
            ->orWhere('slug', 'like', "%$keyword%")
            ->get();

        if (count($genres) > 0 || count($games) > 0 || count($platforms) > 0 || count($developers) > 0) {
            return response()->json([
                'genres' => $genres,
                'games' => $games,
                'platforms' => $platforms,
                'developers' => $developers
            ]);
        } else {
            return response()->json([]);
        }
    }
}
