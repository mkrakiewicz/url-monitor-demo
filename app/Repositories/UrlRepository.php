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
    public function getLatestForUserWithAverages(User $user, int $minuteLimit = 10): Collection
    {
        $urlsRelation = $user->urls();
        $statsRelation = $user->stats();
        $createdAt = "{$statsRelation->getModel()->getTable()}.{$statsRelation->createdAt()}";

        $time = now()->subMinutes($minuteLimit);
        $stats = $urlsRelation->withCount([
            'stats as avg_loading_time' => function (Builder $query) use ($createdAt, $time) {
                return $query
                    ->select(\DB::raw('avg(total_loading_time)'))
                    ->latest()
                    ->where($createdAt, '>', $time);
            },
            'stats as avg_redirect_count' => function (Builder $query) use ($createdAt, $time) {
                return $query
                    ->select(\DB::raw('avg(redirects_count)'))
                    ->latest()
                    ->where($createdAt, '>', $time);
            }
        ])->latest()->get();
        return $stats;
    }
}
