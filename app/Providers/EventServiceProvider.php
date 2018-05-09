<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Test' => [
            'App\Listeners\TestListener',
        ],
        'App\Events\UserAction' => [
            'App\Listeners\ResourceUpdate',
        ],
        'App\Events\UserAction' => [
            'App\Listeners\ResourceUpdate',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
