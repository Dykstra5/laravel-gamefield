<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
}
