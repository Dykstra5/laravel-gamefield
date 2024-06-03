<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use function Pest\Laravel\post;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $posts = Post::query()
            ->withCount('reactions')
            ->withCount('comments')
            ->with([
                'latest5Comments' => function ($query) use ($userId) {
                    $query->withCount('commentReactions') // Contar reacciones de comentarios
                        ->with(['commentReactions' => function ($query) use ($userId) {
                            $query->where('user_id', $userId); // Filtrar reacciones del usuario
                        }]);
                },
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->latest()->paginate(5);

        $posts = PostResource::collection($posts);

        if ($request->wantsJson()) {
            return $posts;
        }

        return Inertia::render('Home', [
            'posts' => $posts
        ]);
    }
}
