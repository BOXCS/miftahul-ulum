<?php

namespace App\Services;

use Ably\AblyRest;

class AblyService
{
    protected $ably;

    public function __construct()
    {
        $this->ably = new AblyRest(env('ABLY_API_KEY'));
    }

    public function publishMessage($channelName, $eventName, $data)
    {
        $this->ably->channels->get($channelName)->publish($eventName, $data);
    }
}
