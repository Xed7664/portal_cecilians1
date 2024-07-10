<?php

namespace App\Livewire\Settings;

use Livewire\{Component};
use Livewire\Attributes\Rule;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeLibrary;

class Qrcode extends Component
{
    public $qrCodeData;

    public function mount($user)
    {
        // Set the data you want to encode in the QR code
        $qrCodeData = $user->getSchoolID();

        // Get the current theme from the session
        $currentTheme = session('theme', 'light');

         // Set the color based on the theme
         $color = $currentTheme === 'dark' ? [13, 110, 253] : [53, 9, 9]; // Example: White for dark theme, Black for light theme

        // Generate the QR code and store it in the $qrCodeData property
        // Generate the QR code with custom color and store it in the $qrCodeData property
        $this->qrCodeData = base64_encode(QrCodeLibrary::format('png')->size(200)->color(...$color)->generate($qrCodeData));
    }

    public function render()
    {
        return view('livewire.settings.qrcode', ['qrCodeData' => $this->qrCodeData]);
    }

}
