<?php

namespace App\Livewire\Settings;

use Livewire\{Component};
use Livewire\Attributes\Rule;
use App\Models\User;

class Connection extends Component
{

    public $connectGoogle;

    public $socialFacebook;
    public $socialTwitter;
    public $socialInstagram;
    public $socialYoutube;
    public $socialTiktok;

    public $fieldEditing = null;

    #[Rule('required', message: 'The username is required.')]
    #[Rule('regex:/^[A-Za-z0-9.]*$/', message: 'The username can only contain alphabets, numbers, and periods.')]
    public $fieldData = null;

    public function mount($user)
    {
        $this->connectGoogle = !empty($user->google_id);

        $this->socialFacebook =  $user->facebook_link;
        $this->socialTwitter =  $user->twitter_link;
        $this->socialInstagram = $user->instagram_link;
        $this->socialYoutube = $user->youtube_link;
        $this->socialTiktok = $user->tiktok_link;
    }

    public function linkService($service){
        

        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Trying to connect to service.',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function editField($fieldName)
    {
        if ($fieldName === 'socialFacebook') {
            $this->fieldData = $this->socialFacebook;
        }

        if ($fieldName === 'socialTwitter') {
            $this->fieldData = $this->socialTwitter;
        }

        if ($fieldName === 'socialInstagram') {
            $this->fieldData = $this->socialInstagram;
        }

        if ($fieldName === 'socialYoutube') {
            $this->fieldData = $this->socialYoutube;
        }

        if ($fieldName === 'socialTiktok') {
            $this->fieldData = $this->socialTiktok;
        }

        $this->fieldEditing = $fieldName;
    }

    public function saveField($fieldName)
    {
        $this->validate();

        if ($fieldName === 'socialFacebook') {
            $this->socialFacebook = $this->fieldData;

            User::find(auth()->user()->id)->update(['facebook_link' => $this->fieldData]);
        }

        if ($fieldName === 'socialTwitter') {
            $this->socialTwitter = $this->fieldData;

            User::find(auth()->user()->id)->update(['twitter_link' => $this->fieldData]);
        }

        if ($fieldName === 'socialInstagram') {
            $this->socialInstagram = $this->fieldData;

            User::find(auth()->user()->id)->update(['instagram_link' => $this->fieldData]);
        }

        if ($fieldName === 'socialYoutube') {
            $this->socialYoutube = $this->fieldData;

            User::find(auth()->user()->id)->update(['youtube_link' => $this->fieldData]);
        }


        if ($fieldName === 'socialTiktok') {
            $this->socialTiktok = $this->fieldData;

            User::find(auth()->user()->id)->update(['tiktok_link' => $this->fieldData]);
        }

        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Connection updated successfully.',
            'type' => 'success',
            'sound' => null,
        ]);

        $this->fieldEditing = null;
    }

    public function cancelEdit($fieldName)
    {
        if ($fieldName === 'socialFacebook') {
            $this->socialFacebook = null;

            User::find(auth()->user()->id)->update(['facebook_link' => null]);
        }

        if ($fieldName === 'socialTwitter') {
            $this->socialTwitter = null;

            User::find(auth()->user()->id)->update(['twitter_link' => null]);
        }

        if ($fieldName === 'socialInstagram') {
            $this->socialInstagram = null;

            User::find(auth()->user()->id)->update(['instagram_link' => null]);
        }

        if ($fieldName === 'socialYoutube') {
            $this->socialYoutube = null;

            User::find(auth()->user()->id)->update(['youtube_link' => null]);
        }

        if ($fieldName === 'socialTiktok') {
            $this->socialTiktok = null;

            User::find(auth()->user()->id)->update(['tiktok_link' => null]);
        }

        $this->fieldEditing = null;

        $this->dispatch('show-toast', [
            'title' => 'Success',
            'message' => 'Connection updated successfully.',
            'type' => 'success',
            'sound' => null,
        ]);
    }

    public function submitForm()
    {
        
    }

    public function render()
    {
        return view('livewire.settings.connection');
    }
}
