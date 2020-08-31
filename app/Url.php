<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'url',
        'avg_total_loading_time',
        'avg_redirects_count',
        'last_status'
    ];

    public function requests()
    {
        return $this->hasMany(UrlRequest::class);
    }

    public function stats()
    {
        return $this->hasManyThrough(UrlRequestStat::class, UrlRequest::class);
    }

    public function allStats()
    {
        return $this->hasMany(UrlRequestStat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
