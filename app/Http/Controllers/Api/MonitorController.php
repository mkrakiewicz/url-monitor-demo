<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMonitorsRequest;
use App\Jobs\GetStatsForUrls;
use App\Repositories\UrlRequestRepository;
use App\Services\BulkUrlPersistenceService;
use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Transformers\StatCollectionTransformer;
use App\Url;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

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
     * @var UrlRequestRepository
     */
    private $urlRequestStatRepository;

    public function __construct(
        BulkUrlPersistenceService $bulkUrlPersistenceService,
        UrlRequestRepository $urlRequestRepository,
        BulkHttpStatsFetcherService $fetcherService
    ) {
        $this->bulkUrlPersistenceService = $bulkUrlPersistenceService;
        $this->fetcherService = $fetcherService;
        $this->urlRequestStatRepository = $urlRequestRepository;
    }

    /**
     * @param AddMonitorsRequest $request
     * @param StatCollectionTransformer $statsTransformer
     * @return ResponseFactory|Response
     * @throws \Throwable
     */
    public function store(User $user, AddMonitorsRequest $request)
    {
        $urlsToAdd = (array)$request->get('items', []);
        $urlsCreated = $this->bulkUrlPersistenceService->saveMany($user, $urlsToAdd);

        $this->dispatchNow(new GetStatsForUrls($urlsCreated, config('url-monitor.store.stat-timeout')));

        return response([
            'urlsAddedCount' => $urlsCreated->count(),
            'urlsAddedIds' => [$urlsCreated->pluck('id')->values()]
        ]);
    }

    /**
     * @param User $user
     * @param Url $url
     * @return array
     */
    public function show(User $user, Url $url)
    {
        $stats = Cache::remember(
            $url->getStatsCacheKey(),
            \DateInterval::createFromDateString('1 minute'),
            function () use ($user, $url) {
                return $this->urlRequestStatRepository->getRecentForUrl($url,
                    config('url-monitor.index.last-stats-minutes')
                );
            });
        return ['url' => $url, 'requests' => $stats];
    }
}
