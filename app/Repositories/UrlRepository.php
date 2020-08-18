<?php

namespace App\Repositories;

use App\Url;
use App\User;

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
}
