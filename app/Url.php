<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = ['url'];

    public function requests()
    {
        return $this->hasMany(UrlRequest::class);
    }

    public function stats()
    {
        return $this->hasMany(UrlRequestStat::class);
    }
}
