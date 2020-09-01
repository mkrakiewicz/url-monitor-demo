<?php

namespace App\Events\Url;

use App\Url;

class UrlCreated
{
    /**
     * @var Url
     */
    private $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }
}
