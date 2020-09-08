<?php

namespace App\Providers;

use App\Events\UrlRequest\UrlRequestCreated;
use App\Events\UrlRequest\UrlRequestUpdated;
use App\Events\UrlRequestStatCreated;
use App\Listeners\LastRequestIdCacheRemover;
use App\Listeners\UrlAveragesUpdater;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        UrlRequestCreated::class => [
            UrlAveragesUpdater::class,
            LastRequestIdCacheRemover::class
        ],
        UrlRequestStatCreated::class => [
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
