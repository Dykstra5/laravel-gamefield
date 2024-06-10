<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PostAttachmentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index(Request $request, $username)
    {
        try {
            $user = User::where('username', $username)->firstOrFail();

            $followsUser = false;

            $roles = [];

            if (!Auth::guest()) {
                $followsUser = Follower::where('user_id', $user->id)->where('follower_id', Auth::id())->exists();
                $roles = Role::all();
            }

            $followers = Follower::where('user_id', $user->id)->count();

            $postsQuery = Post::postsForTimeline(Auth::id(), true)->where('user_id', $user->id);
            $posts = $postsQuery->paginate(10);
            $posts = PostResource::collection($posts);

            if ($request->wantsJson()) {
                return $posts;
            }

            $roles = Role::all();

            $postsQueryDeleted = Post::postsForTimeline(Auth::id(), true)
                ->withTrashed() // Incluir posts que han sido eliminados suavemente    
                ->whereNotNull('deleted_by')
                ->whereNotNull('deleted_at');

            $deletedPosts = $postsQueryDeleted->get();
            $deletedPosts = PostResource::collection($deletedPosts);

            $following = User::query()
                ->select('users.*')
                ->join('followers', 'user_id', 'users.id')
                ->where('follower_id', $user->id)
                ->get();

            $allAttachments = DB::table('posts')
                ->join('post_attachments', 'posts.id', '=', 'post_attachments.post_id')
                ->where('posts.user_id', $user->id)
                ->whereNull('posts.deleted_at') // Filtra los posts eliminados suavemente
                ->select('post_attachments.*')
                ->get();

            return Inertia::render('Profile/View', [
                'mustVerifyEmail' => $user instanceof MustVerifyEmail,
                'status' => session('status'),
                'success' => session('success'),
                'user' => new UserResource($user),
                'followsUser' => $followsUser,
                'followers' => $followers,
                'posts' => $posts,
                'usersFollowing' => UserResource::collection($following),
                'roles' => $roles,
                'allAttachments' => PostAttachmentResource::collection($allAttachments),
                'deletedPosts' => $deletedPosts
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile', $request->user()->username);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update user cover image.
     */
    public function updateImages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover' => ['nullable', 'image', 'max:20800'], // 20 MB
            'avatar' => ['nullable', 'image', 'max:20800'], // 20 MB
        ], [
            'cover' => 'La imagen de cabecera no se ha podido subir correctamente',
            'avatar' => 'La imagen de avatar no se ha podido subir correctamente',
            'cover.image' => 'La imagen de la cabecera debe ser una imagen.',
            'cover.max' => 'La imagen de la cabecera no puede ser mayor a 20MB.',
            'avatar.image' => 'La imagen del avatar debe ser una imagen.',
            'avatar.max' => 'La imagen del avatar no puede ser mayor a 20MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $user = $request->user();

        try {
            DB::beginTransaction();

            $cover = $data['cover'] ?? null;
            $avatar = $data['avatar'] ?? null;

            $success = '';
            if ($cover) {
                if ($user->cover_path) {
                    Storage::disk('public')->delete($user->cover_path);
                }
                $path = $cover->store('user-' . $user->id, 'public');
                $user->update(['cover_path' => $path]);
                $success = 'La cabecera se ha actualizado correctamente';
            }

            if ($avatar) {
                if ($user->avatar_path) {
                    Storage::disk('public')->delete($user->avatar_path);
                }
                $path = $avatar->store('user-' . $user->id, 'public');
                $user->update(['avatar_path' => $path]);
                $success = 'La imagen de perfil se ha actualizado correctamente';
            }

            DB::commit();
            return back()->with('success', $success);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Hubo un problema al subir las imágenes.'])->withInput();
        }
    }
}
