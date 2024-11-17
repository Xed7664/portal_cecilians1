<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $username, $url)
    {
        $this->user = $user;
        $this->username = $username;
        $this->url = $url;
    }
    
    public function build()
    {
        return $this->view('emails.employee_verification')
            ->subject('Set Your Credentials')
            ->with([
                'user' => $this->user,
                'username' => $this->username,
                'url' => $this->url,
            ]);
    }
    

   
}
