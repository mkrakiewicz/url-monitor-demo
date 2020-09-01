<?php

namespace App;

use App\Events\UrlRequest\UrlRequestCreated;
use App\Events\UrlRequest\UrlRequestUpdated;
use Illuminate\Database\Eloquent\Model;

class UrlRequest extends Model
{
    protected $fillable = ['status'];
    protected $with = ['url'];

    protected $dispatchesEvents = [
        'created' => UrlRequestCreated::class,
        'updated' => UrlRequestUpdated::class
    ];

    public function stat()
    {
        return $this->hasOne(UrlRequestStat::class);
    }

    public function url()
    {
        return $this->belongsTo(Url::class);
    }
//
//    public function user()
//    {
//        return $this->(User::class, Url::class);
//    }
}
