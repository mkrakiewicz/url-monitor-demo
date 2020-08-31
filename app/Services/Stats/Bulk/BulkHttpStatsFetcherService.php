<?php

namespace App\Services\Stats\Bulk;

use App\Services\Stats\StatCreatorService;
use App\Structs\StatCounter;
use App\Structs\UrlStatsBuffer;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Str;
use Log;
use function GuzzleHttp\Promise\settle;

class BulkHttpStatsFetcherService
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var StatCreatorService
     */
    private $statCreatorService;
    private $maxRedirects;

    public function __construct(StatCreatorService $counterService, int $maxRedirects)
    {
        $this->client = new Client();
        $this->statCreatorService = $counterService;
        $this->maxRedirects = $maxRedirects;
    }

    /**
     * @param iterable $urls
     * @param float $maxTimeout
     * @return UrlStatsBuffer
     * @throws \Throwable
     */
    public function getBulkStats(iterable $urls, float $maxTimeout = 2.0): UrlStatsBuffer
    {
        $counter = new StatCounter();

        $promises = $this->createPromises($urls, $counter, $maxTimeout);

        $this->waitForPromises($promises);

        Log::debug('Counter', ['counter', $counter]);

        return $this->statCreatorService->collectStats($urls, $counter);
    }

    /**
     * @param iterable $urls
     * @param StatCounter $counter
     * @param float $timeout
     * @return iterable
     */
    private function createPromises(iterable $urls, StatCounter $counter, float $timeout): iterable
    {
        $promises = [];
        foreach ($urls as $url) {
            $promises[$url] = $this->createPromise($url, $counter, $timeout);
        }
        return $promises;
    }

    /**
     * @param $url
     * @param StatCounter $counter
     * @param float $timeout
     * @return PromiseInterface
     */
    private function createPromise(string $url, StatCounter $counter, float $timeout): PromiseInterface
    {
        return $this->client->getAsync($url, [
            'timeout' => $timeout,
            'http_errors' => false,
            'allow_redirects' => ['max' => $this->maxRedirects,],
            'on_stats' => function (TransferStats $stats) use ($url, $counter) {

                Log::debug('Handler Stats Dump', [var_export($stats->getHandlerStats(), true)]);

                if ($this->isRedirectResponse($stats)) {
                    $counter->addRedirects($url, 1);
                }

                $totalTime = $stats->getHandlerStat('total_time');

                Log::debug("$url: added time: $totalTime");

                $counter->addTime($url, $totalTime);
                if ($stats->hasResponse()) {
                    $counter->setStatus($url, $stats->getResponse()->getStatusCode());
                }
            }
        ])->then(function ($data) use ($url, $counter) {
            $counter->setCompleted($url);
            Log::info("Request to $url completed.");
        }, function () use ($url) {
            Log::info("Request to $url was rejected!");
        });
    }

    /**
     * @param TransferStats $stats
     * @return bool
     */
    private function isRedirectResponse(TransferStats $stats): bool
    {
        return $stats->hasResponse() && Str::startsWith((string)$stats->getResponse()->getStatusCode(), ['3']);
    }

    /**
     * @param iterable $promises
     * @return void
     * @throws \Throwable
     */
    private function waitForPromises(iterable $promises): void
    {
        settle($promises)->wait();
    }
}
