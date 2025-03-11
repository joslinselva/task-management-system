<?php

namespace App\Providers;

use App\Events\TaskCompleted;
use App\Listeners\LogTaskCompletion;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        TaskCompleted::class => [
            LogTaskCompletion::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // ... any additional event registration logic ...
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}