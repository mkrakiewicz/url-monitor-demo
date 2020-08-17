<?php

namespace App\Structs;

class UrlStat
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var float
     */
    private $totalTime;
    /**
     * @var int
     */
    private $numberOfRedirects;

    public function __construct(string $url, float $totalTime, int $numberOfRedirects)
    {
        $this->url = $url;
        $this->totalTime = $totalTime;
        $this->numberOfRedirects = $numberOfRedirects;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return float
     */
    public function getTotalTime(): float
    {
        return $this->totalTime;
    }

    /**
     * @return int
     */
    public function getNumberOfRedirects(): int
    {
        return $this->numberOfRedirects;
    }
}
