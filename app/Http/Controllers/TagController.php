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

            $tagElement = $modelClass::where('slug', $slug)->firstOrFail();


            $postTagIds = PostTag::where('type', $type)
                ->where('tag_id', $tagElement->id)
                ->pluck('post_id');

            $postsQuery = Post::postsForTimeline(Auth::id(), true)
                ->whereIn('id', $postTagIds);

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

            $followsTag = FavouriteTag::where('user_id', Auth::id())
                ->where('type', $type)
                ->where('tag_id', $tagElement->id)
                ->exists();

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

            return Inertia::render('Tag/View', [
                'posts' => $posts,
                'success' => session('success'),
                'usersFollowing' => UserResource::collection($following),
                'tagElement' => $tagElement,
                'type' => $type,
                'followsTag' => $followsTag,
                'tagsFollowing' => TagResource::collection($tagsFollowing),
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dashboard');
        }
    }

    public function followTag($id, $type)
    {
        FavouriteTag::create([
            'user_id' => Auth::id(),
            'type' => $type,
            'tag_id' => $id
        ]);

        return back()->with('success', 'Ha sido añadido a favoritos');
    }

    public function unfollowTag($id, $type)
    {
        $message = 'No tienes añadido este tema a favoritos';
        if (!Auth::guest()) {
            if (FavouriteTag::where('user_id', Auth::id())->where('type', $type)->where('tag_id', $id)) {
                $message = 'Ha sido eliminado de favoritos';
                FavouriteTag::query()->where('user_id', Auth::id())->where('type', $type)->where('tag_id', $id)->delete();
            }
        }

        return back()->with('success', $message);
    }
}
