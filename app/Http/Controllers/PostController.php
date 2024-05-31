<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        Post::create($data);

        return back() ;
    }

    public function destroy(Post $post)
    {
        $id = Auth::id();

        if ($post->user_id !== $id) {
            return response("No tienes permiso para eliminar este post", 403);
        }

        $post->delete();

        return back();
    }
}
