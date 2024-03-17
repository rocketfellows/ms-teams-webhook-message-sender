<?php

namespace rocketfellows\MSTeamsWebhookMessageSender;

use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\request\ConnectorException;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;

interface MSTeamsWebhookMessageSenderInterface
{
    /**
     * @throws EmptyIncomingWebhookUrlException
     * @throws InvalidIncomingWebhookUrlException
     * @throws EmptyMessageException
     * @throws ConnectorException
     */
    public function sendMessage(Connector $connector, Message $message): void;
}
