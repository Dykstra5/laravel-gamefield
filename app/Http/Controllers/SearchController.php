<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use Illuminate\Http\Request;

class SearchController extends Controller
{
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
