<?php

namespace App\Listeners;

use App\Events\UrlRequest\UrlRequestEvent;
use App\Repositories\UrlRepository;
use App\Repositories\UrlRequestStatRepository;
use App\Url;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
    public function handle($event)
    {
        $urlRequest = $event->getUrlRequest();
        $url = $urlRequest->url;  //$this->urlRepository->findByUrlRequestStat();

        $minuteLimit = config('url-monitor.index.last-stats-minutes');

        $averages = [
            'avg_total_loading_time' => $this->urlRequestStatRepository->getRecentStatsAvg(
                $url, 'total_loading_time', $minuteLimit
            ),
            'avg_redirects_count' => $this->urlRequestStatRepository->getRecentStatsAvg(
                $url, 'redirects_count', $minuteLimit
            ),
            'last_status' => optional($this->urlRequestStatRepository->getLatestStat($url, $minuteLimit))->status
        ];

        Log::info('Updating averages url', ['url' => $url, 'averages' => $averages]);

        $url->update($averages);

        $this->clearCache($url);
    }

    /**
     * @param Url $url
     */
    private function clearCache(Url $url): void
    {
        Log::info('Clearing cache for url', [$url]);

        Cache::forget($url->getStatsCacheKey());
        foreach ($url->users as $user) {
            Cache::forget($user->getUrlsCacheKey());
        }
    }
}
