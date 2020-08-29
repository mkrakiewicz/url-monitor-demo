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

    public function avgLoadingTime()
    {
        $urlIdSelect = "{$this->allStats()->getModel()->getTable()}.{$this->allStats()->getForeignKeyName()}";
        return $this->stats()
            ->selectRaw("avg(total_loading_time) as aggregate")
            ->groupBy('laravel_through_key');
    }
}
