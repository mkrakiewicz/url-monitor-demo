<?php

namespace App\Transformers;

use App\Structs\UrlStat;

class StatsTransformer
{
    /**
     * @param UrlStat $urlStat
     * @return iterable
     */
    public function transform(UrlStat $urlStat): iterable
    {
        return [
            'totalTime' => round($urlStat->getTotalTime(), 3),
            'redirectsCount' => (int)$urlStat->getNumberOfRedirects()
        ];
    }
}
