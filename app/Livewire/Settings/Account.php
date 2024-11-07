<?php

namespace App\Livewire\Settings;

use Livewire\{Component, WithFileUploads};
use Livewire\Attributes\Rule;
use App\Models\User;

class Account extends Component
{

    use WithFileUploads;

    public $fullname;
    public $email;
    public $birthday;
    public $birthplace;
    public $religion;
    public $citizenship;
    public $address;
    public $type;

    #[Rule('nullable', message: 'Please enter a valid bio.')]
    #[Rule('max:101', message: 'The bio is too long.')]
    public $bio;

    #[Rule('nullable|image|max:800', message: 'Please upload a valid avatar (JPG, GIF, or PNG) with a maximum size of 800KB.')]
    public $avatar;

    public $originalValues; // Store original values here
    
    // Temporary image variable for real-time preview
    public $avatarReset;

    public function mount($user)
    {
        $this->avatarReset = false;
        $this->fullname = $user->getFullname();
        $this->email = $user->email;

        // Check if student data is available
        $student = $user->student;
        $this->birthday = optional($student)->Birthday;
        $this->birthplace = optional($student)->BirthPlace;
        $this->religion = optional($student)->Religion;
        $this->citizenship = optional($student)->Citizenship;
        $this->address = optional($student)->Address;
        
        $this->type = $user->type;
        $this->bio = $user->bio;

        $this->originalValues = [
            'fullname' => $this->fullname,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'birthplace' => $this->birthplace,
            'religion' => $this->religion,
            'citizenship' => $this->citizenship,
            'address' => $this->address,
            'type' => $this->type,
            'bio' => $this->bio,
        ];
    }

    public function submitForm()
    {

        $this->validate();

        // Update the user's bio in the database
        User::find(auth()->user()->id)->update(['bio' => $this->bio]);

        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Account updated successfully.',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function resetAvatar()
    {
        $this->avatarReset = true;

        $user = User::find(auth()->user()->id);
    
        // Update the avatar field in the database to 'default-profile.png'
        $user->update(['avatar' => 'default-profile.png']);

        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Avatar has been reset successfully.',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function render()
    {
        return view('livewire.settings.account');
    }
}
