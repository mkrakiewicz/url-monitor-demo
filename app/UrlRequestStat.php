<?php

namespace App;

use App\Events\UrlRequestStatCreated;
use Illuminate\Database\Eloquent\Model;

class UrlRequestStat extends Model
{
    protected $fillable = [
        'total_loading_time',
        'redirects_count',
        'status'
    ];

    protected $dispatchesEvents = [
        'created' => UrlRequestStatCreated::class
    ];

    public function request()
    {
        return $this->belongsTo(UrlRequest::class);
    }

    public function url()
    {
        return $this->belongsTo(Url::class);
    }


//    public function user()
//    {
//        return $this->hasOneThrough(User::class, UrlRequest::class);
//    }
}
