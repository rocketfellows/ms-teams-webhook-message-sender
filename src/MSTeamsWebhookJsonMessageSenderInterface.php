<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\InvalidJsonMessageException;

interface MSTeamsWebhookJsonMessageSenderInterface
{
    /**
     * @throws InvalidIncomingWebhookUrlException
     * @throws EmptyIncomingWebhookUrlException
     * @throws InvalidJsonMessageException
     */
    public function sendJsonMessage(Connector $connector, string $jsonMessage): void;
}
