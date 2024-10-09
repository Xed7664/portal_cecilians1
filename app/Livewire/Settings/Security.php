<?php

namespace App\Livewire\Settings;

use Livewire\{Component};
use Livewire\Attributes\Rule;
use App\Models\User;
use Illuminate\Support\Facades\{Hash};

class Security extends Component
{

    #[Rule('required', message: 'Old password is required.')]
    #[Rule('regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/', message: 'Password format is invalid.')]
    #[Rule('min:8', message: 'Password should be at least 8 characters long.')]
    public $password;

    #[Rule('required', message: 'New password is required.')]
    #[Rule('regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/', message: 'Password format is invalid.')]
    #[Rule('min:8', message: 'Password should be at least 8 characters long.')]
    public $newPassword;

    #[Rule('required', message: 'Confirm new password is required.')]
    #[Rule('regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/', message: 'Password format is invalid.')]
    #[Rule('min:8', message: 'Password should be at least 8 characters long.')]
    public $confirmNewPassword;

    public function changePassword()
    {
        $this->validate();
        
        // Check if the current password matches the user's password
        if (!Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'The current password is incorrect.');
            return;
        }

        // Ensure the new password is not equal to the old password
        if (Hash::check($this->newPassword, auth()->user()->password)) {
            $this->addError('newPassword', 'The new password cannot be the same as the current password.');
            return;
        }

        // Change the password
        auth()->user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Password updated successfully.',
            'type' => 'success',
            'sound' => null,
        ]);

        // Clear the form fields
        $this->password = '';
        $this->newPassword = '';
        $this->confirmNewPassword = '';
    }

    public function render()
    {
        return view('livewire.settings.security');
    }
}
