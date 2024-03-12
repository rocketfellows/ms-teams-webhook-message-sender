<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;

interface MSTeamsWebhookJsonMessageSenderInterface
{
    public function sendJsonMessage(Connector $connector, string $jsonMessage): void;
}
