<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\InvalidJsonMessageException;

interface MSTeamsWebhookJsonMessageSenderInterface
{
    /**
     * @throws InvalidJsonMessageException
     */
    public function sendJsonMessage(Connector $connector, string $jsonMessage): void;
}
