<?php

namespace App\Repositories;

use App\Url;
use App\UrlRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UrlRequestRepository
{
    /**
     * @param Url $url
     * @return UrlRequest
     */
    public function createCompleted(Url $url): UrlRequest
    {
        /** @var UrlRequest $model */
        $model = $url->requests()->make(['status' => 'completed']);
//        $model->user()->associate($url->user);
        $model->save();
        return $model;
    }

    /**
     * @param Url $url
     * @return UrlRequest
     */
    public function createPending(Url $url): UrlRequest
    {
        /** @var UrlRequest $model */
        $model = $url->requests()->make(['status' => 'pending']);
//        $model->user()->associate($url->user);
        $model->save();
        return $model;
    }

    /**
     * @param int $olderThanMinutes
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function deleteOldStats(int $olderThanMinutes): Collection
    {
        $requests = UrlRequest::where('created_at', '<', Carbon::now()->subMinutes($olderThanMinutes))->get();
        $requests->each(function (UrlRequest $urlRequest) {
            $urlRequest->stat()->delete();
            $urlRequest->delete();
        });
        return $requests;
    }

    /**
     * @param Url $url
     * @param int $minuteLimit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentForUrl(Url $url, int $minuteLimit): Collection
    {
        $createdAt = "{$url->requests()->getModel()->getTable()}.{$url->requests()->createdAt()}";
        $time = now()->subMinutes($minuteLimit);
        return $url->requests()->with('stat')->where($createdAt, '>', $time)->get();
    }

    public function getLastRequest(User $user): ?UrlRequest
    {
        return Cache::rememberForever(
            $user->getLastRequestIdCacheKey(),
            function () use ($user) {
                return $user->requests()->latest()->first();
            });
    }
}
