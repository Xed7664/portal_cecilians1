<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function followOrUnfollow(User $user)
    {
        $authenticatedUser = auth()->user();
        
        // Check if the user exists
        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => 'User not found.',
            ]);
        }

        if ($authenticatedUser->isFollowing($user)) {
            // If the user is already following, unfollow
            $authenticatedUser->following()->detach($user);
            $message = 'You have unfollowed @' . $user->username;
            $follow = false;
        } else {
            // If the user is not following, follow
            $authenticatedUser->following()->attach($user);
            $message = 'You are now following @' . $user->username;
            $follow = true;
        }

        $followersCount = $user->followers()->count(); // Get the followers count of the user

        // Determine the proper label for followers
        $followersLabel = $followersCount === 1 ? 'follower' : 'followers';

        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => [
                'followers_count' => $followersCount,
                'followers_label' => $followersLabel,
                'following' => $follow
            ],
        ]);
    }

}
