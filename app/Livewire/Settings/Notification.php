<?php

namespace App\Livewire\Settings;

use Livewire\{Component};
use Livewire\Attributes\Rule;
use App\Models\User;

class Notification extends Component
{


   
    public function submitForm()
    {
        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Notifications updated successfully.',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function resetForm()
    {
        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Notification restored successfully.',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function render()
    {
        return view('livewire.settings.notification');
    }
}
