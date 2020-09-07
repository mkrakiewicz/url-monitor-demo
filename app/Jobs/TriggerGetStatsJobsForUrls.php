<?php

namespace App\Jobs;

use App\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TriggerGetStatsJobsForUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int
     */
    private $concurrentUrlRequests;

    /**
     * Create a new job instance.
     *
     * @param int $concurrentUrlRequests
     */
    public function __construct(int $concurrentUrlRequests)
    {
        $this->concurrentUrlRequests = $concurrentUrlRequests;
    }

    /**
     * @return void
     */
    public function handle()
    {
        Log::info("Concurrent request / chunk count: {$this->concurrentUrlRequests}");

        Url::chunk($this->concurrentUrlRequests, function (Collection $urls) {

            dispatch(
                app()->make(GetStatsForUrls::class, ['urls' => $urls])
            );

            Log::info("Dispatched job for {$urls->count()} urls");
        });
    }
}
