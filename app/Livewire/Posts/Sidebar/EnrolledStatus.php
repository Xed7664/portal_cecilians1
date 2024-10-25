<?php
namespace App\Http\Livewire\Posts\Sidebar;

use Livewire\Component;

class EnrolledStatus extends Component
{
    public $student;

    public function mount()
    {
        // Assuming that the authenticated user is a student
        $this->student = auth()->user()->student;
    }

    public function render()
    {
        return view('livewire.posts.sidebar.enrollment-status', [
            'student' => $this->student,
        ]);
    }
}
