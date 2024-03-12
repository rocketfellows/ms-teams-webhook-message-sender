<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;

interface MSTeamsWebhookArrayMessageSenderInterface
{
    public function sendMessageFromArray(Connector $connector, array $messageData): void;
}
