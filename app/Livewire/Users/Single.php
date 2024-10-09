<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class Single extends Component
{
    public $student;

    public $isRegistered;
    public $registeredUserDetails;

    public $isFollowing;

    public function mount(Student $student){
        $this->student = $student;

        // Check if the student is registered based on the user relationship
        $this->isRegistered = $student->isRegistered();
        $this->registeredUserDetails = $this->isRegistered ? $student->user : null;

        // Get the authenticated user
        $user = Auth::user();

        // Check if the authenticated user is following the current user
        $this->isFollowing = $user->isFollowing($student->user);
    }

    public function followUser(){

        $this->dispatch('follow-user', [
            'follow-user' => $this->registeredUserDetails
        ])->to('profile.profile-component');

        $this->isFollowing = !$this->isFollowing;

    }

    public function render()
    {
        return view('livewire.users.single');
    }
}
