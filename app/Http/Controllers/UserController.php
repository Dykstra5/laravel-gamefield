<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\delete;

class UserController extends Controller
{
    public function followUser(User $user)
    {
        Follower::create([
            'user_id' => $user->id,
            'follower_id' => Auth::id()
        ]);

        return back()->with('success', 'Has seguido a este usuario');
    }

    public function unfollowUser(User $user)
    {
        $message = 'No sigues a este usuario';
        if (!Auth::guest()) {
            if (Follower::where('user_id', $user->id)->where('follower_id', Auth::id())->exists()) {
                $message = 'Has dejado de seguir a este usuario';
                Follower::query()->where('user_id', $user->id)->where('follower_id', Auth::id())->delete();
            }
        }

        return back()->with('success', $message);
    }

    public function searchUsers($keyword)
    {
        if (empty($keyword)) {
            return response()->json([]);
        }

        $users = [];

        if ($keyword === '*') {
            $users = User::where('id', '!=', Auth::id())->get();
        } else {
            $users = User::query()->where('name', 'like', "%$keyword%")
                ->orWhere('username', 'like', "%$keyword%")
                ->get();
        }

        return response()->json([
            'usersSearch' => UserResource::collection($users),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back();
    }
}
