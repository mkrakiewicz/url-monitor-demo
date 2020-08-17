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
            'redirects_count' => $stats->getNumberOfRedirects()
        ]);
        $statModel->url()->associate($url);
        $statModel->save();
        return $statModel;
    }

    /**
     * @param Url $url
     * @param int $minuteLimit
     * @return Collection
     */
    public function getLatestStats(Url $url, int $minuteLimit = 10): Collection
    {
        $stats = $url->stats()->where($url->stats()->createdAt(), '>', now()
            ->subMinutes($minuteLimit))->latest()->get([
            'created_at as time',
            'total_loading_time as loadingTime',
            'redirects_count as redirectCount'
        ]);
        return $stats;
    }
}
