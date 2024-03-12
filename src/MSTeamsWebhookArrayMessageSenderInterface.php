<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;

interface MSTeamsWebhookArrayMessageSenderInterface
{
    /**
     * @throws EmptyIncomingWebhookUrlException
     * @throws InvalidIncomingWebhookUrlException
     */
    public function sendMessageFromArray(Connector $connector, array $messageData): void;
}
