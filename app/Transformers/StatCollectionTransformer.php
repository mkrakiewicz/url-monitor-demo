<?php

namespace App\Transformers;

use App\Structs\UrlStatsBuffer;

class StatCollectionTransformer
{
    /**
     * @var StatsTransformer
     */
    private $statsTransformer;

    public function __construct(StatsTransformer $statsTransformer)
    {
        $this->statsTransformer = $statsTransformer;
    }

    public function transform(UrlStatsBuffer $urlStats, string...$urlsToList): iterable
    {
        $result = [];
        foreach ($urlsToList as $url) {
            $result[$url] = optional($urlStats->getStats($url), function ($value) {
                return $this->statsTransformer->transform($value);
            });
        }
        return $result;
    }
}
