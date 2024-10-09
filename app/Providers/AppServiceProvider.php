<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
        \App\Models\Reaction::class => \App\Policies\ReactionPolicy::class,
    ];
    

    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');
    }
}
