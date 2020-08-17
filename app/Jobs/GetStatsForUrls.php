<?php

namespace App\Jobs;

use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Services\Stats\Bulk\BulkStatsPersistenceService;
use App\Url;
use App\UrlRequest;
use App\UrlRequestStat;
use App\UrlStat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GetStatsForUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Collection|Url[]
     */
    private $urls;
    /**
     * @var int
     */
    private $statTimeout;

    /**
     * @param iterable $urls
     * @param int $statTimeout
     */
    public function __construct(iterable $urls, int $statTimeout)
    {
        $this->urls = collect($urls);
        $this->statTimeout = $statTimeout;
    }

    /**
     * @param BulkHttpStatsFetcherService $statsFetcher
     * @param BulkStatsPersistenceService $statsPersistenceService
     * @return void
     * @throws \Throwable
     */
    public function handle(
        BulkHttpStatsFetcherService $statsFetcher,
        BulkStatsPersistenceService $statsPersistenceService
    ) {
        $urls = $this->urls->pluck('url');

        Log::info("Timeout: $this->statTimeout", ['urls' => $urls]);

        $bulkStats = $statsFetcher->getBulkStats($urls, $this->statTimeout);
        $statsPersistenceService->saveStatsForUrls($bulkStats, ...$this->urls);
    }
}
