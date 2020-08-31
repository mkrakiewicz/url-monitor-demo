<?php

namespace App\Repositories;

use App\Structs\UrlStat;
use App\Url;
use App\UrlRequest;
use App\UrlRequestStat;
use Illuminate\Database\Eloquent\Collection;

class UrlRequestStatRepository
{
    /**
     * @param Url $url
     * @param UrlRequest $request
     * @param UrlStat $stats
     * @return UrlRequestStat
     */
    public function create(Url $url, UrlRequest $request, UrlStat $stats): UrlRequestStat
    {
        /** @var UrlRequestStat $statModel */
        $statModel = $request->stat()->make([
            'total_loading_time' => $stats->getTotalTime(),
            'redirects_count' => $stats->getNumberOfRedirects(),
            'status' => $stats->getStatus()
        ]);
        $statModel->url()->associate($url);
//        $statModel->user()->associate($url->user);
        $statModel->save();
        return $statModel;
    }

    /**
     * @param Url $url
     * @param int $minuteLimit
     * @return UrlRequestStat|null
     */
    public function getLatestStat(Url $url, int $minuteLimit): ?UrlRequestStat
    {
        /** @var UrlRequestStat|null $model */
        $model = $this->getRecentQuery($url, $minuteLimit)->first();
        return $model;
    }

    /**
     * @param Url $url
     * @param string $avg
     * @param int $minuteLimit
     * @return float|null
     */
    public function getRecentStatsAvg(Url $url, string $avg, int $minuteLimit): ?float
    {
        return $this->getRecentQuery($url, $minuteLimit)->avg($avg);
    }

    /**
     * @param Url $url
     * @param int $minuteLimit
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    private function getRecentQuery(Url $url, int $minuteLimit)
    {
        $createdAt = "{$url->stats()->getModel()->getTable()}.{$url->stats()->createdAt()}";
        $time = now()->subMinutes($minuteLimit);
        return $url->allStats()->where($createdAt, '>', $time);
    }

}
