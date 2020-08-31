<?php

namespace App;

use App\Events\UrlRequestCreated;
use Illuminate\Database\Eloquent\Model;

class UrlRequest extends Model
{
    protected $fillable = ['status'];

    protected $dispatchesEvents = [
        'created' => UrlRequestCreated::class
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
