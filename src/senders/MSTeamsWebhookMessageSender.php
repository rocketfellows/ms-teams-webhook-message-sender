<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\senders;

use GuzzleHttp\Client;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface;

class MSTeamsWebhookMessageSender implements MSTeamsWebhookMessageSenderInterface
{
    private $client;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }
}
