<?php

namespace App\Structs;

class StatCounter
{
    private const REDIRECTS_KEY = 'redirects';
    private const TIME_KEY = 'time';
    private const STATUS = 'status';

    private $stats = [];

    public function addTime(string $url, float $amount)
    {
        $this->increment($url, self::TIME_KEY, $amount);
    }

    public function addRedirects(string $url, int $amount)
    {
        $this->increment($url, self::REDIRECTS_KEY, $amount);
    }

    public function getTime(string $url): ?float
    {
        return $this->stats[$url][self::TIME_KEY] ?? null;
    }

    public function getRedirects(string $url): ?int
    {
        return $this->stats[$url][self::REDIRECTS_KEY] ?? null;
    }

    /**
     * @param string $url
     * @param string $key
     * @param float $amount
     */
    private function increment(string $url, string $key, float $amount): void
    {
        if (!isset($this->stats[$url][$key])) {
            $this->stats[$url][$key] = 0;
        }
        $this->stats[$url][$key] += $amount;
    }

    public function setCompleted(string $url)
    {
        $this->stats[$url]['ok'] = true;
    }

    public function isCompleted(string $url): bool
    {
        return $this->stats[$url]['ok'] ?? false;
    }

    public function setStatus(string $url, int $statusCode)
    {
        $this->stats[$url][static::STATUS] = $statusCode;
    }

    /**
     * @param string $url
     * @return int|null
     */
    public function getStatusCode(string $url): ?int
    {
        return $this->stats[$url][static::STATUS];
    }
}
