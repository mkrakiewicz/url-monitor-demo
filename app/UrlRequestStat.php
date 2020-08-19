<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlRequestStat extends Model
{
    protected $fillable = [
        'total_loading_time',
        'redirects_count'
    ];

    public function request()
    {
        return $this->belongsTo(UrlRequestStat::class);
    }

    public function url()
    {
        return $this->belongsTo(Url::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
