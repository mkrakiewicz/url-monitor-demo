<?php

namespace App\Providers;

use App\Console\Commands\DeleteOldStats;
use App\Jobs\GetStatsForUrls;
use App\Jobs\TriggerGetStatsJobsForUrls;
use App\Observers\UserObserver;
use App\Services\Stats\Bulk\BulkHttpStatsFetcherService;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->addContextualBinding(TriggerGetStatsJobsForUrls::class, '$concurrentUrlRequests', function () {
            return config('url-monitor.jobs.chunk-url-count');
        });

        $this->app->addContextualBinding(GetStatsForUrls::class, '$statTimeout', function () {
            return config('url-monitor.jobs.stat-timeout');
        });

        $this->app->addContextualBinding(BulkHttpStatsFetcherService::class, '$maxRedirects', function () {
            return config('url-monitor.stats.max-redirects');
        });

        $this->app->addContextualBinding(DeleteOldStats::class, '$deleteTimeMinutes', function () {
            return config('url-monitor.old-stats-minutes');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
