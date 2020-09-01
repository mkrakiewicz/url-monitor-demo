<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMonitorsRequest;
use App\Repositories\UrlRequestRepository;
use App\Repositories\UrlRequestStatRepository;
use App\Services\BulkUrlPersistenceService;
use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\Transformers\StatCollectionTransformer;
use App\Url;
use App\User;
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
    public function store(User $user, AddMonitorsRequest $request, StatCollectionTransformer $statsTransformer)
    {
        $urlsToAdd = (array)$request->get('items', []);
        $this->bulkUrlPersistenceService->saveMany($user, $urlsToAdd);

        if ($isRequestingStats = (boolean)$request->get('stats', false)) {
            $stats = $this->fetcherService->getBulkStats($urlsToAdd, config('url-monitor.store.stat-timeout'));
            $transformed = $statsTransformer->transform($stats, ...$urlsToAdd);
            Cache::forget("user-urls:{$user->id}");
            return response('', 200, [config('url-monitor.store.stats-header-name') => Json::encode($transformed)]);
        }
    }

    /**
     * @param Url $url
     * @return array
     */
    public function index(User $user, Url $url)
    {
        $stats = Cache::remember(
            "url-stats-{$url->id}",
            \DateInterval::createFromDateString('1 minute'),
            function () use ($url) {
                return $this->urlRequestStatRepository->getRecentWithStats(
                    $url,
                    config('url-monitor.index.last-stats-minutes')
                );
            });
        return ['url' => $url, 'requests' => $stats];
    }
}
