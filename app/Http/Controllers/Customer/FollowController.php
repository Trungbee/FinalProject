<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class FollowController extends Controller
{
    public function toggle(User $user)
{
    $currentUser = auth()->user();
    if ($currentUser->id === $user->id) return response()->json(['error' => '...'], 400);

    $isFollowing = $currentUser->isFollowing($user);

    if ($isFollowing) {
        $currentUser->following()->detach($user->id);
    } else {
        $currentUser->following()->attach($user->id);

        // GỬI THÔNG BÁO CHO NGƯỜI ĐƯỢC THEO DÕI
        $user->notify(new \App\Notifications\UserFollowed($currentUser));
    }

    return response()->json([
        'is_following' => !$isFollowing,
        'followers_count' => $user->followers()->count()
    ]);
}
}
