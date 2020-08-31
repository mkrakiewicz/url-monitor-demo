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
    /**
     * @var int
     */
    private $status;

    public function __construct(string $url, float $totalTime, int $numberOfRedirects, int $status)
    {
        $this->url = $url;
        $this->totalTime = $totalTime;
        $this->numberOfRedirects = $numberOfRedirects;
        $this->status = $status;
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

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
