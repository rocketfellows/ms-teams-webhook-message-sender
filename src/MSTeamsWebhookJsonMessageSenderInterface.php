<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageDataException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\InvalidJsonMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\request\ConnectorException;

interface MSTeamsWebhookJsonMessageSenderInterface
{
    /**
     * @throws InvalidIncomingWebhookUrlException
     * @throws EmptyIncomingWebhookUrlException
     * @throws EmptyMessageDataException
     * @throws InvalidJsonMessageException
     * @throws ConnectorException
     */
    public function sendJsonMessage(Connector $connector, string $jsonMessage): void;
}
