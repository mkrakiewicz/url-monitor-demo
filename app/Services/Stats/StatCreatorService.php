<?php

namespace App\Services\Stats;

use App\Structs\StatCounter;
use App\Structs\UrlStat;
use App\Structs\UrlStatsBuffer;

class StatCreatorService
{
    /**
     * @param iterable $urls
     * @param StatCounter $counter
     * @return UrlStatsBuffer
     */
    public function collectStats(iterable $urls, StatCounter $counter): UrlStatsBuffer
    {
        $stats = new UrlStatsBuffer();

        foreach ($urls as $url) {
            $stats->addStat($url, $this->createStat($url, $counter));
        }
        return $stats;
    }

    private function createStat(string $url, StatCounter $counter): ?UrlStat
    {
        if (!$counter->isCompleted($url)) {
            return null;
        }
        $redirects = (int)$counter->getRedirects($url);
        $status = (int)$counter->getStatusCode($url);

        $time = $counter->getTime($url);
        return new UrlStat($url, $time, $redirects, $status);
    }
}
