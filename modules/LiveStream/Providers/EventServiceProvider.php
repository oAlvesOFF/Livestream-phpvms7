<?php

namespace Modules\LiveStream\Providers;

use App\Events\TestEvent;
use Modules\LiveStream\Listeners\TestEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        \App\Events\PirepStateChange::class => [
            \Modules\LiveStream\Listeners\CheckLiveOnStateChange::class,
        ],
        \App\Events\PirepAccepted::class => [
            \Modules\LiveStream\Listeners\ProcessInteractionsOnPirepAccepted::class,
        ],
        \App\Events\ProfileUpdated::class => [
            \Modules\LiveStream\Listeners\UpdateStreamSettings::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
