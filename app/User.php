<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasRelationships;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function urls()
    {
        return $this->belongsToMany(Url::class);
    }

    public function requests()
    {
        return $this->hasManyDeep(UrlRequest::class, ['url_user', Url::class]);
    }

    public function stats()
    {
//        return $this->hasManyDeep(UrlRequestStat::class, [Url::class, UrlRequest::class]);
        return $this->hasManyDeep(UrlRequestStat::class, [Url::class, UrlRequest::class]);
    }

    public function getUrlsCacheKey()
    {
        return "user-urls:{$this->id}";
    }
}
