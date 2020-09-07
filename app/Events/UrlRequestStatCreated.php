<?php

namespace App\Events;

use App\UrlRequest;
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
    protected $urlRequestStat;

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

    /**
     * @return UrlRequest
     */
    public function getUrlRequest(): UrlRequest
    {
        if ($request = $this->getUrlRequestStat()->request) {
            return $request;
        }

        return UrlRequest::findOrFail($this->getUrlRequestStat()->url_request_id);
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
