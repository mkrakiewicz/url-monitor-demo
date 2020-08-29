<?php

namespace App\Repositories;

use App\Url;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
        $statsRelation = $user->stats();
        $statsCreatedAt = "{$statsRelation->getModel()->getTable()}.{$statsRelation->createdAt()}";
        $requestsRelation = $user->requests();
        $requestsCreatedAt = "{$requestsRelation->getModel()->getTable()}.{$requestsRelation->createdAt()}";

        $time = now()->subMinutes($minuteLimit);
        $stats = $user->urls()->withCount([
            'requests' => function (Builder $query) use ($requestsCreatedAt, $time) {
                return $query->where($requestsCreatedAt, '>', $time);

            },
            'stats as avg_loading_time' => function (Builder $query) use ($statsCreatedAt, $time) {
                return $query
                    ->select(\DB::raw('avg(total_loading_time)'))
                    ->latest()
                    ->where($statsCreatedAt, '>', $time);
            },
            'stats as avg_redirect_count' => function (Builder $query) use ($statsCreatedAt, $time) {
                return $query
                    ->select(\DB::raw('avg(redirects_count)'))
                    ->latest()
                    ->where($statsCreatedAt, '>', $time);
            }
        ])->latest()->get();
        return $stats;
    }
}
