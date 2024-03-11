<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;

interface MSTeamsWebhookTextSenderInterface
{
    public function sendText(Connector $connector, string $text): void;
}
