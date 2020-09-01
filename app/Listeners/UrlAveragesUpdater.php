<?php

namespace App\Listeners;

use App\Events\UrlRequest\UrlRequestEvent;
use App\Events\UrlRequestStatCreated;
use App\Repositories\UrlRepository;
use App\Repositories\UrlRequestStatRepository;

class UrlAveragesUpdater
{
    /**
     * @var UrlRepository
     */
    private $urlRepository;
    /**
     * @var UrlRequestStatRepository
     */
    private $urlRequestStatRepository;

    /**
     * Create the event listener.
     *
     * @param UrlRepository $urlRepository
     * @param UrlRequestStatRepository $urlRequestStatRepository
     */
    public function __construct(UrlRepository $urlRepository, UrlRequestStatRepository $urlRequestStatRepository)
    {
        //
        $this->urlRepository = $urlRepository;
        $this->urlRequestStatRepository = $urlRequestStatRepository;
    }

    /**
     * Handle the event.
     *
     * @param UrlRequestEvent $event
     * @return void
     */
    public function handle(UrlRequestEvent $event)
    {
        $url = $event->getUrlRequest()->url;  //$this->urlRepository->findByUrlRequestStat();
        $minuteLimit = config('url-monitor.index.last-stats-minutes');
        $time = now()->subMinutes($minuteLimit);
        $url->update([
            'avg_total_loading_time' => $this->urlRequestStatRepository->getRecentStatsAvg(
                $url, 'total_loading_time', $minuteLimit
            ),
            'avg_redirects_count' => $this->urlRequestStatRepository->getRecentStatsAvg(
                $url, 'redirects_count', $minuteLimit
            ),
            'last_status' => optional($this->urlRequestStatRepository->getLatestStat($url, $minuteLimit))->status
        ]);
    }
}
