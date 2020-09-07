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
        ])
            ->latest()->get();
    }

}
