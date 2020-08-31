<?php

namespace App\Events;

use App\UrlRequestStat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UrlRequestStatCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var UrlRequestStat
     */
    private $urlRequestStat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UrlRequestStat $urlRequestStat)
    {
        //
        $this->urlRequestStat = $urlRequestStat;
    }

    /**
     * @return UrlRequestStat
     */
    public function getUrlRequestStat(): UrlRequestStat
    {
        return $this->urlRequestStat;
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
