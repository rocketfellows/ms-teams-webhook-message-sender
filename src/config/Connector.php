<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\config;

class Connector
{
    private $incomingWebhookUrl;

    public function __construct(
        string $incomingWebhookUrl
    ) {
        $this->incomingWebhookUrl = $incomingWebhookUrl;
    }

    public function getIncomingWebhookUrl(): string
    {
        return $this->incomingWebhookUrl;
    }
}
