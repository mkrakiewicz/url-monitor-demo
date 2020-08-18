<?php

use App\UrlRequest;
use App\UrlRequestStat;
use Illuminate\Database\Seeder;

class UrlRequestStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requests = UrlRequest::all();
        $requests->each(function (UrlRequest $urlRequest) {
            $urlRequest->stat()->create(
                factory(UrlRequestStat::class)->state('demo')->make([
                    'url_id' => $urlRequest->url_id,
                    'user_id' => $urlRequest->user_id,
                    'created_at' => $urlRequest->created_at
                ])->toArray()
            );
        });
    }
}
