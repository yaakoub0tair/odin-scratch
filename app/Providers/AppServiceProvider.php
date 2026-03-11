<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\LinkPolicy;
use App\Models\Link;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Link::class, LinkPolicy::class);

        // Register events and listeners
        Event::listen(
            \App\Events\LinkCreated::class,
            \App\Listeners\LogActivity::class,
        );

        Event::listen(
            \App\Events\LinkUpdated::class,
            \App\Listeners\LogActivity::class,
        );

        Event::listen(
            \App\Events\LinkDeleted::class,
            \App\Listeners\LogActivity::class,
        );

        Event::listen(
            \App\Events\LinkShared::class,
            \App\Listeners\LogActivity::class,
        );
    }
}
