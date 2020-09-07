<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getStatsCacheKey(): string
    {
        return "url-stats-{$this->id}";
    }
}
