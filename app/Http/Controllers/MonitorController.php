<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMonitorsRequest;
use App\Repositories\UrlRequestStatRepository;
use App\Services\BulkUrlPersistenceService;
use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Transformers\StatCollectionTransformer;
use App\Url;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Psy\Util\Json;

class MonitorController extends Controller
{
    /**
     * @var BulkUrlPersistenceService
     */
    private $bulkUrlPersistenceService;
    /**
     * @var \App\Services\Stats\Bulk\BulkHttpStatsFetcherService
     */
    private $fetcherService;
    /**
     * @var UrlRequestStatRepository
     */
    private $urlRequestStatRepository;

    public function __construct(
        BulkUrlPersistenceService $bulkUrlPersistenceService,
        UrlRequestStatRepository $urlRequestStatRepository,
        BulkHttpStatsFetcherService $fetcherService
    ) {
        $this->bulkUrlPersistenceService = $bulkUrlPersistenceService;
        $this->fetcherService = $fetcherService;
        $this->urlRequestStatRepository = $urlRequestStatRepository;
    }

    /**
     * @param AddMonitorsRequest $request
     * @param StatCollectionTransformer $statsTransformer
     * @return ResponseFactory|Response
     * @throws \Throwable
     */
    public function store(AddMonitorsRequest $request, StatCollectionTransformer $statsTransformer)
    {
        $urlsToAdd = (array)$request->get('items', []);
        $this->bulkUrlPersistenceService->saveMany($urlsToAdd);

        if ($isRequestingStats = (boolean)$request->get('stats', false)) {
            $stats = $this->fetcherService->getBulkStats($urlsToAdd, config('url-monitor.store.stat-timeout'));
            $transformed = $statsTransformer->transform($stats, ...$urlsToAdd);
            return response('', 200, [config('url-monitor.store.stats-header-name') => Json::encode($transformed)]);
        }
    }

    /**
     * @param Url $url
     * @return array
     */
    public function index(Url $url)
    {
        $stats = Cache::remember(
            "url-stats-{$url->id}",
            \DateInterval::createFromDateString('1 minute'),
            function () use ($url) {
                return $this->urlRequestStatRepository->getLatestStats(
                    $url,
                    config('url-monitor.index.last-stats-minutes')
                );
            });
        return [$url->url => $stats];
    }
}
