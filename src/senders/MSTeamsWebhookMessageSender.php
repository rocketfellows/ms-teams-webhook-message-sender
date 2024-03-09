<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\senders;

use GuzzleHttp\Client;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface;

class MSTeamsWebhookMessageSender implements MSTeamsWebhookMessageSenderInterface
{
    private $client;

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    public function sendMessage(Connector $connector, Message $message): void
    {
        // TODO: Implement sendMessage() method.
        $this->validateConnector($connector);
    }

    /**
     * @throws EmptyIncomingWebhookUrlException
     * @throws InvalidIncomingWebhookUrlException
     */
    private function validateConnector(Connector $connector): void
    {
        if (empty($connector->getIncomingWebhookUrl())) {
            throw new EmptyIncomingWebhookUrlException();
        }

        if (!filter_var($connector->getIncomingWebhookUrl(), FILTER_VALIDATE_URL)) {
            throw new InvalidIncomingWebhookUrlException();
        }
    }
}
