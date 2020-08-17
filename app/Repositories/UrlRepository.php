<?php

namespace App\Repositories;

use App\Url;

class UrlRepository
{
    /**
     * @param $url
     * @return Url|\Illuminate\Database\Eloquent\Model
     */
    public function persistByUrl(string $url): Url
    {
        return Url::firstOrCreate(['url' => $url]);
    }
}
