<?php

namespace App\Http\Controllers;

use App\Models\{User, Post};
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile($username)
    {
        // Retrieve the user based on the provided username
        $user = User::where('username', $username)->first();
        
        // Retrieve the currently authenticated user
        $currentUser = auth()->user();

        if (!$user) {
            abort(404); // Display a 404 error if the user is not found
        }

        // Check if the current user and the profile user have a mutual follow relationship
        $isMutualFollow = $currentUser ? $currentUser->isMutualFollow($user) : false;

        // Check if the user is official
        $isOfficial = $currentUser ? $currentUser->isOfficial() : false;

        // Include the currently authenticated user's own posts and the profile user's posts
        // - if they have a mutual follow relationship
        // - or if the currently authenticated user is official
        $posts = Post::withTrashed()
            ->whereIn('user_id', ($isMutualFollow || $isOfficial) ? [$user->id, $currentUser->id] : [$user->id])
            ->with(['user', 'comments' => function ($query) {
                // Select the latest comment for each post, if it exists
                $query->latest()->with(['replies' => function ($subQuery) {
                    // Select the latest reply for the latest comment, if it exists
                    $subQuery->latest();
                }]);
            }])
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->where('status', 'publish')
            ->paginate(5, ['*'], 'page', 1)
            ->items();

        foreach ($posts as $post) {
            if (is_numeric($post->views_count)) {
                $post->increment('views_count');
            }
        }

        // Pass only the 'user' and 'posts' variables to the profile view
        return view('profiles.show', compact('user', 'posts'));
    }

    public function showTrophy($username)
    {
        // Retrieve the user based on the provided username
        $user = User::where('username', $username)->first();

        if (!$user) {
            abort(404); // Display a 404 error if the user is not found
        }

        // Pass the user data to the profile view
        return view('profiles.trophy', compact('user'));
    }
}
