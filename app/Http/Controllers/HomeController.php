<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
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


        return Inertia::render('Home', [
            'posts' => $posts,
            'usersFollowing' => UserResource::collection($following),
        ]);
    }
}
