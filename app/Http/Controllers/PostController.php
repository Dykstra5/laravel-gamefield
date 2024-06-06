<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\CommentReaction;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostReaction;
use App\Models\PostTag;
use App\Models\User;
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

        $tags = $data['tags'] ?? [];

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

            $tags = $data['tags'] ?? [];


            foreach ($tags as $tag) {
                PostTag::create([
                    'post_id' => $post->id,
                    'type' => $tag['type'],
                    'tag_id' => $tag['tag_id'],
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

        return redirect()->route('dashboard'); // Redirige a la página de inicio si el post ya no existe
    }

    public function destroyAsAdmin(Post $post)
    {
        $post->deleted_by = Auth::id();
        $post->save();
        $post->delete();

        return back();
    }

    public function restoreAsAdmin($postId)
    {
        $post = Post::withTrashed()->findOrFail($postId);
        if ($post->trashed()) {
            $post->restore();
            $post->deleted_by = null;
            $post->save();

            return response()->json(['message' => 'El post ha sido restaurado correctamente'], 200);
        } else {
            return response()->json(['error' => 'El post no está eliminado'], 404);
        }
    }

    public function view(Post $post)
    {
        $post->loadCount('reactions');
        $post->loadCount('comments');
        $post->load([
            'latest5Comments' => function ($query) {
                $query->withCount('commentReactions'); // Contar reacciones de comentarios
            },
        ]);
        return inertia('Post/View', [
            'post' => new PostResource($post),
        ]);
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
            'comment' => ['required', 'max:1000'],
        ], [
            'comment.required' => 'El comentario no puede estar vacío',
            'comment.max' => 'El comentario no puede tener más de 1000 carácteres',
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

    public function destroyComment(Comment $comment)
    {
        $id = Auth::id();

        if ($comment->user_id !== $id) {
            return response("No tienes permiso para eliminar este comentario", 403);
        }

        CommentReaction::where('comment_id', $comment->id)->delete();
        $comment->delete();

        return response('', 204);
    }

    public function commentLike(Comment $comment)
    {
        $userId = Auth::id();
        $commentReaction = CommentReaction::where('comment_id', $comment->id)->where('user_id', $userId)->first();

        if ($commentReaction) {
            $has_liked = false;
            $commentReaction->delete();
        } else {
            $has_liked = true;
            CommentReaction::create([
                'comment_id' => $comment->id,
                'type' => 'like',
                'user_id' => Auth::id()
            ]);
        }

        $likes = CommentReaction::where('comment_id', $comment->id)->count();

        return response([
            'comment_id' => $comment->id,
            'commentLikes' => $likes,
            'has_liked_comment' => $has_liked
        ]);
    }
}
