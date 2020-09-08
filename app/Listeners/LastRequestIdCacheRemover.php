<?php

namespace App\Listeners;

use App\Events\UrlRequest\UrlRequestCreated;
use App\UrlRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LastRequestIdCacheRemover
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(UrlRequestCreated $event)
    {
        $urlRequest = $event->getUrlRequest();
        $users = $urlRequest->url->users;
        Log::info("Clearing cache for {$users->count()} users ", [UrlRequest::class, $urlRequest->id]);
        foreach ($users as $user) {
            Cache::forget($user->getLastRequestIdCacheKey());
        }
    }
}
