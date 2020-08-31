<?php

namespace App\Events;

use App\UrlRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UrlRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
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
