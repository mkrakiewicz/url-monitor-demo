<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UrlRepository;
use App\User;
use Cache;
use DateInterval;

class UrlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /**
     * Show the application dashboard.
     *
     * @param User $user
     * @param UrlRepository $urlRepository
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(User $user, UrlRepository $urlRepository)
    {
        return Cache::remember(
            $user->getUrlsCacheKey(),
            DateInterval::createFromDateString("1 minute"),
            function () use ($urlRepository, $user) {
                $minuteLimit = config('url-monitor.index.last-stats-minutes');
                return $urlRepository->getLatestForUserWithAverages($user, $minuteLimit);
            });
    }
}
