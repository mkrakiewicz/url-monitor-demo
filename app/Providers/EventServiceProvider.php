<?php

namespace App\Providers;

use App\Events\UrlRequestCreated;
use App\Events\UrlRequestStatCreated;
use App\Listeners\UrlAveragesUpdater;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UrlRequestStatCreated::class => [
            UrlAveragesUpdater::class
        ],
        UrlRequestCreated::class => [
            UrlAveragesUpdater::class
        ]
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
