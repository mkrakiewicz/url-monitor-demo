<?php

namespace App\Events\UrlRequest;

use App\UrlRequest;

abstract class UrlRequestEvent
{

    /**
     * @var UrlRequest
     */
    protected $urlRequest; // Must be protected to serialize

    /**
     * Create a new event instance.
     *
     * @param UrlRequest $urlRequest
     */
    public function __construct(UrlRequest $urlRequest)
    {
        $this->urlRequest = $urlRequest;
    }

    /**
     * @return UrlRequest
     */
    public function getUrlRequest(): UrlRequest
    {
        return $this->urlRequest;
    }
}
