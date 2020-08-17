<?php

namespace App\Structs;

class UrlStatsBuffer
{
    /**
     * @var UrlStat[]
     */
    private $urlStats = [];

    public function addStat(string $url, ?UrlStat $stat)
    {
        $this->urlStats[$url] = $stat;

    }

    public function getStats(string $url): ?UrlStat
    {
        return $this->urlStats[$url] ?? null; // Cannot use "data_get" because keys contain dots.
    }
}
