<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\{Auth};
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileComponent extends Component
{

    #[On('follow-user')]
    public function followUser(User $userId)
    {
        try {
            // Get the target user based on the provided user ID or throw an exception if not found
            $targetUser = User::findOrFail($userId->id);

            // Check if the authenticated user is trying to follow themselves
            if (Auth::user()->id === $targetUser->id) {
                // Display a message or handle the case where the user is trying to follow themselves
                $this->dispatch('show-toast', [
                    'title' => 'Invalid Action',
                    'message' => 'You cannot follow or unfollow yourself.',
                    'type' => 'warning',
                    'sound' => null,
                ]);
            } else {
                // Check if the current user is already following the target user
                if (Auth::user()->isFollowing($targetUser)) {
                    // If already following, unfollow the user
                    Auth::user()->following()->detach($targetUser->id);

                    $this->dispatch('show-toast', [
                        'title' => 'Unfollow',
                        'message' => 'You have unfollowed @' . $targetUser->username,
                        'type' => 'success',
                        'sound' => null,
                    ]);
                } else {
                    // If not following, follow the user
                    Auth::user()->following()->attach($targetUser->id);

                    $this->dispatch('show-toast', [
                        'title' => 'Follow',
                        'message' => 'You have followed @' . $targetUser->username,
                        'type' => 'success',
                        'sound' => null,
                    ]);
                }
            }
        } catch (ModelNotFoundException $e) {
            // User not found, handle the exception (e.g., display a message)
            $this->dispatch('show-toast', [
                'title' => 'User Not Found',
                'message' => 'The user you are trying to follow does not exist.',
                'type' => 'error',
                'sound' => null,
            ]);
        }
        // Optionally, you can emit an event or perform other actions here
    }

    public function render()
    {
        return view('livewire.profile.profile-component');
    }
}
