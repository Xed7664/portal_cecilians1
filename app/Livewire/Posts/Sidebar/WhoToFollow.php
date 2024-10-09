<?php

namespace App\Livewire\Posts\Sidebar;

use Livewire\Component;
use App\Models\User;

class WhoToFollow extends Component
{
    public $usersToFollow;

    public function mount()
    {
        $this->loadUsersToFollow();
    }

    public function loadUsersToFollow()
    {
        // Get users who are not followed by the current user
        $this->usersToFollow = User::whereNotIn('id', auth()->user()->following->pluck('id'))
            ->where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->take(config('portal.newsfeed.users_to_follow_count'))
            ->get()
            ->sortBy('id') // Sort by ID to maintain a consistent order
            ->values(); // Reindex the array keys

        // Check if the authenticated user is following each user and set the "followed" attribute
        $this->usersToFollow->each(function ($user) {
            $user->followed = auth()->user()->isFollowing($user);
            $user->isOfficial = $user->isOfficial();
        });

    }

    public function refresh()
    {
        $this->loadUsersToFollow();
    }

    public function followUser(User $userToFollow)
    {
        // Check if the user is already followed
        $isFollowing = auth()->user()->isFollowing($userToFollow);

        $this->dispatch('follow-user', [
            'follow-user' => $userToFollow
        ])->to('profile.profile-component');

        // Update the entire collection with the new follow/unfollow status
        $this->usersToFollow = $this->usersToFollow->map(function ($user) use ($userToFollow, $isFollowing) {
            if ($user->id === $userToFollow->id) {
                $user->followed = !$isFollowing;
            } else {
                $user->followed = auth()->user()->isFollowing($user->id);
            }
            $user->isOfficial = $user->isOfficial();
            return $user;
        });
        
        $this->render();
    }



    public function render()
    {
        return view('livewire.posts.sidebar.who-to-follow');
    }
}
