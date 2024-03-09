<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\senders;

use GuzzleHttp\Client;

class MSTeamsWebhookMessageSender
{
    private $client;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }
}
