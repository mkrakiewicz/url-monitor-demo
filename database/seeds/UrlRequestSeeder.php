<?php

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
        \App\Url::each(function (\App\Url $url) {
            factory(UrlRequest::class, 10)->create(['url_id' => $url->id]);
        });
    }
}
