<?php

namespace App\Events\UrlRequest;

use App\UrlRequest;

abstract class UrlRequestEvent
{
    /**
     * @var UrlRequest
     */
    private $urlRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UrlRequest $urlRequest)
    {
        //
        $this->urlRequest = $urlRequest;
    }

    /**
     * @return UrlRequest
     */
    public function getUrlRequest(): UrlRequest
    {
        return $this->urlRequest;
    }
//
//
//    /**
//     * Get the channels the event should broadcast on.
//     *
//     * @return \Illuminate\Broadcasting\Channel|array
//     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
