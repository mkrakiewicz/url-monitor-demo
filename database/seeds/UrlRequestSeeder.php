<?php

use App\Url;
use App\UrlRequest;
use Illuminate\Database\Seeder;

class UrlRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Url::each(function (Url $url) {
            factory(UrlRequest::class, 10)->state('demo')->create([
                'url_id' => $url->id,
                'user_id' => $url->user_id,
            ]);
        });
    }
}
