<?php

namespace App\Repositories;

use App\Url;
use App\UrlRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
        $model->user()->associate($url->user);
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
        $model->user()->associate($url->user);
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
}