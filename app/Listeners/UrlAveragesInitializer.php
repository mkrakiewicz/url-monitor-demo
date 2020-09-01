<?php

namespace App\Listeners;

use App\Events\UrlRequest\UrlRequestCreated;
use App\Events\UrlRequestStatCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UrlAveragesInitializer
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UrlRequestCreated $event)
    {
//        $url = $event->getUrlRequest()->url;
//        $url->update([
//            'avg_total_loading_time' => null,
//            'avg_redirects_count' => null,
//            'last_status' => optional($this->urlRequestStatRepository->getLatestStat($url, $minuteLimit))->status
//        ]);
    }
}
