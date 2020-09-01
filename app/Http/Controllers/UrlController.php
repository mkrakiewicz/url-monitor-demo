<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRepository;
use App\User;

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
//        return $user->urls()->withCount(['requests','stats AS stat_time_avg' => function ($query) {
//            $query->select(DB::raw("AVG(amount_total) as paidsum"))->where('status', 'paid');
//        }])->get();
////

//        return $user->find(1)->urls()->with([
//            'stats' => function ($builder) {
//                return $builder->select(\DB::raw("AVG('total_loading_time') as total_loading_time"))->groupBy(['laravel_through_key']);
//            }
//        ])->get(['urls.id'])->dd();

//        \DB::getQueryLog()
        return \Cache::remember("user-urls:{$user->id}",
            \DateInterval::createFromDateString("1 minute"), function () use ($urlRepository, $user) {
                $minuteLimit = config('url-monitor.index.last-stats-minutes');
                return $urlRepository->getLatestForUserWithAverages($user, $minuteLimit);
            });
    }
}
