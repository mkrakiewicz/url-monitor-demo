<?php

namespace App\Repositories;

use App\Url;
use App\UrlRequestStat;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class UrlRepository
{
    /**
     * @param $url
     * @return Url|\Illuminate\Database\Eloquent\Model
     */
    public function persistByUrl(User $user, string $url): Url
    {
        return $user->urls()->firstOrCreate(['url' => $url]);
    }


    /**
     * @param Url $url
     * @param int $minuteLimit
     * @return Collection
     */
    public function getLatestForUserWithAverages(User $user, int $minuteLimit): Collection
    {
        $requestsRelation = $user->requests();
        $requestsCreatedAt = "{$requestsRelation->getModel()->getTable()}.{$requestsRelation->createdAt()}";
        $statsCreatedAt = "{$user->stats()->getModel()->getTable()}.{$user->stats()->createdAt()}";

        $time = now()->subMinutes($minuteLimit);

        return $user->urls()->withCount([
            'requests' => function (Builder $query) use ($requestsCreatedAt, $time) {
                return $query->where($requestsCreatedAt, '>', $time);

            }
        ])->addSelect([
            'last_status' =>
                UrlRequestStat::select(\DB::raw("{$user->stats()->getModel()->getTable()}.status"))
                    ->where($statsCreatedAt, '>', $time)
                    ->where(UrlRequestStat::getModel()->getTable() . '.url_id', '=', DB::raw('urls.id'))
                    ->orderBy($statsCreatedAt, 'desc')
                    ->limit(1)
        ])->get();

//        $results = DB::select(DB::raw("select
//  urls.*,
//  avg(url_request_stats.total_loading_time) as avg_loading_time, avg(url_request_stats.redirects_count) as avg_redirect_count,
//  url_request_stats.status as last_status
// from `urls`
//left join url_requests on url_requests.id = urls.id
//left join url_request_stats on url_requests.id = url_request_stats.url_request_id
//where `urls`.`user_id` = {$user->id} and url_requests.created_at > {$time->getTimestamp()}
//group by urls.id
//order by url_request_stats.created_at desc, url_requests.created_at desc,  `urls`.`created_at` desc"));
//        return Url::hydrate($results);
//        return $this->withSelects($user, $minuteLimit);
//        return $this->withJoins($user, $minuteLimit);
    }

    /**
     * @param UrlRequestStat $urlRequestStat
     * @return Url
     */
    public function findByUrlRequestStat(UrlRequestStat $urlRequestStat): Url
    {
        return $urlRequestStat->url;
    }

    /**
     * @param User $user
     * @param int $minuteLimit
     * @return Collection
     */
    private function withJoins(User $user, int $minuteLimit): Collection
    {
        $statsRelation = $user->stats();
        $statsCreatedAt = "{$statsRelation->getModel()->getTable()}.{$statsRelation->createdAt()}";
        $requestsRelation = $user->requests();
        $requestsCreatedAt = "{$requestsRelation->getModel()->getTable()}.{$requestsRelation->createdAt()}";
        $time = now()->subMinutes($minuteLimit);


        $stats = $user->urls()
            ->join('url_requests', 'url_requests.url_id', '=', 'urls.id')
            ->orderBy($requestsCreatedAt, 'desc')->get([
                'urls.*',
                \DB::raw('(select avg(total_loading_time) from url_request_stats join url_requests on url_requests.id = url_request_stats.url_request_id where url_requests.url_id = urls.id) as `avg_loading_time`'),
                \DB::raw('(select avg(redirects_count) from url_request_stats join url_requests on url_requests.id = url_request_stats.url_request_id where url_requests.url_id = urls.id) as `avg_redirects_count`'),
                \DB::raw('(select count(*) from url_requests where url_requests.url_id = urls.id) as `requests_count`')
            ]);
        return $stats;
    }

    /**
     * @param User $user
     * @param int $minuteLimit
     * @return Collection
     */
    private function withSelects(User $user, int $minuteLimit): Collection
    {
        $statsRelation = $user->stats();
        $statsCreatedAt = "{$statsRelation->getModel()->getTable()}.{$statsRelation->createdAt()}";
        $requestsRelation = $user->requests();
        $requestsCreatedAt = "{$requestsRelation->getModel()->getTable()}.{$requestsRelation->createdAt()}";
        $time = now()->subMinutes($minuteLimit);

//        $statsRelation = $statsRelation
//            ->orderBy('url_request_stats.created_at', 'desc')
//            ->where($statsCreatedAt, '>', $time);
//        Url::get

        $stats = $user->urls()
//            ->join('url_requests', 'url_requests.url_id', '=', 'urls.id')
//                ->join('url_request_stats','url_requests.id','=','url_request_stats.id')
//            ->withCount([
//                'requests' => function (Builder $query) use ($requestsCreatedAt, $time) {
//                    return $query->where($requestsCreatedAt, '>', $time);
//
//                }
//            ])
            ->addSelect([
                'avg_loading_time' =>
//                function (Builder $query) use ($statsCreatedAt, $time) {
//                return $query
                    UrlRequestStat::select(\DB::raw('avg(total_loading_time)'))
                        ->where('url_request_stats.url_request_id', '=', 'url_requests.id')
                        ->where($statsCreatedAt, '>', $time)
                ,
                'avg_redirect_count' =>
                    $user->stats()->select(\DB::raw('avg(redirects_count)'))
                        ->orderBy('url_request_stats.created_at', 'desc')
                        ->where($statsCreatedAt, '>', $time)
                ,
                'last_status' =>
                    $user->stats()->select(\DB::raw("{$statsRelation->getModel()->getTable()}.status"))
                        ->where($statsCreatedAt, '>', $time)
                        ->orderBy('url_request_stats.created_at', 'desc')
//

                ,
            ])
            ->orderBy($requestsCreatedAt, 'desc')->get();
//        $stats = $user->urls()
//            ->withCount([
//            'requests' => function (Builder $query) use ($requestsCreatedAt, $time) {
//                return $query->where($requestsCreatedAt, '>', $time);
//
//            }
//        ])
//            ->addSelect([
//                'avg_loading_time' =>
////                function (Builder $query) use ($statsCreatedAt, $time) {
////                return $query
//                    $user->stats()->select(\DB::raw('avg(total_loading_time)'))
//                        ->orderBy('url_request_stats.created_at', 'desc')
//                        ->where($statsCreatedAt, '>', $time)
//                ,
//                'avg_redirect_count' =>
//                    $user->stats()->select(\DB::raw('avg(redirects_count)'))
//                        ->orderBy('url_request_stats.created_at', 'desc')
//                        ->where($statsCreatedAt, '>', $time)
//                ,
//                'last_status' =>
//                    $user->stats()->select(\DB::raw("{$statsRelation->getModel()->getTable()}.status"))
//
//                ,
//            ])->latest()->get();
        return $stats;
    }
}
