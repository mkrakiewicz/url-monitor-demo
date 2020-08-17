<?php

namespace App\Services\Stats\Bulk;

use App\Repositories\UrlRequestRepository;
use App\Repositories\UrlRequestStatRepository;
use App\Structs\UrlStat;
use App\Structs\UrlStatsBuffer;
use App\Url;
use App\UrlRequest;

class BulkStatsPersistenceService
{
    /**
     * @var UrlRequestRepository
     */
    private $urlRequestRepository;
    /**
     * @var UrlRequestStatRepository
     */
    private $urlRequestStatRepository;

    public function __construct(
        UrlRequestRepository $urlRequestRepository,
        UrlRequestStatRepository $urlRequestStatRepository
    ) {
        $this->urlRequestRepository = $urlRequestRepository;
        $this->urlRequestStatRepository = $urlRequestStatRepository;
    }

    /**
     * @param UrlStatsBuffer $bulkStats
     * @param Url[] $urlsToSaveTo
     */
    public function saveStatsForUrls(UrlStatsBuffer $bulkStats, Url...$urlsToSaveTo)
    {
        foreach ($urlsToSaveTo as $url) {
            $stats = $bulkStats->getStats($url->url);

            $request = $this->createRequest($url, $stats);
            if ($stats) {
                $this->urlRequestStatRepository->create($url, $request, $stats);
            }
        }
    }

    private function createRequest(Url $url, ?UrlStat $stats): UrlRequest
    {
        if ($stats) {
            return $this->urlRequestRepository->createCompleted($url);
        }

        return $this->urlRequestRepository->createPending($url);
    }
}
