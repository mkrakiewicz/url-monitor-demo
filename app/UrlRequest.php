<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlRequest extends Model
{
    protected $fillable = ['status'];

    public function stat()
    {
        return $this->hasOne(UrlRequestStat::class);
    }

    public function url()
    {
        return $this->belongsTo(Url::class);
    }
}
