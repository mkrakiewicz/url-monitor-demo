<?php

namespace App\Events\UrlRequest;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UrlRequestCreated extends UrlRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
}
