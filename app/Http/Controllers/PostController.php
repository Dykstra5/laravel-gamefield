<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validated();
        $user = $request->user();

        $allPaths = [];

        try {
            $post = Post::create($data);

            $attachments = $data['attachments'] ?? [];

            foreach ($attachments as $attachment) {
                $path = $attachment->store('attachment/post-' . $post->id, 'public');
                $allPaths[] = $path;
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'created_by' => $user->id,
                    'created_by' => $user->id,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            foreach ($allPaths as $path) {
                Storage::disk('public')->delete($path);
            }
            DB::rollBack();
        }

        return back();
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

    public function downloadAttachment(PostAttachment $attachment)
    {
        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'File not found.');
        }

        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
    }

    public function postLike(Post $post)
    {
        $userId = Auth::id();
        $reaction = PostReaction::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($reaction) {
            $has_liked = false;
            $reaction->delete();
        } else {
            $has_liked = true;
            PostReaction::create([
                'post_id' => $post->id,
                'type' => 'like',
                'user_id' => Auth::id()
            ]);
        }

        $likes = PostReaction::where('post_id', $post->id)->count();

        return response([
            'likes' => $likes,
            'has_liked' => $has_liked
        ]);
    }

    public function storeComment(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'comment' => ['required'],
        ], [
            'comment.required' => 'El comentario no puede estar vacío',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $data = $validator->validated();

        $comment = Comment::create([
            'post_id' => $post->id,
            'comment' => nl2br($data['comment']),
            'user_id' => Auth::id()
        ]);

        return response(new CommentResource($comment), 201);
    }
}
