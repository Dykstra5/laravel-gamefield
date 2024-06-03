<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index($username)
    {
        try {
            $user = User::where('username', $username)->firstOrFail();

            return Inertia::render('Profile/View', [
                'mustVerifyEmail' => $user instanceof MustVerifyEmail,
                'status' => session('status'),
                'success' => session('success'),
                'user' => new UserResource($user),
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

        return to_route('profile', $request->user());
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
            'cover' => ['nullable', 'image', 'max:20800'], // 2048 KB = 2 MB
            'avatar' => ['nullable', 'image', 'max:20800'], // 2048 KB = 2 MB
        ], [
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

        return back()->with('success', $success);
    }
}
