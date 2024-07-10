<?php

namespace App\Providers;

use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->view('emails.verify_email', ['user' => $notifiable, 'verificationUrl' => $url]);
        });

        Gate::define('delete-repost', [PostPolicy::class, 'deleteRepost']);
        Gate::define('delete-post', [PostPolicy::class, 'deletePost']);
        Gate::define('react-post', [PostPolicy::class, 'reactToPost']);
    }
}
