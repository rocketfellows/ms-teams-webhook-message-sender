<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\senders;

use GuzzleHttp\Client;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface;

class MSTeamsWebhookMessageSender implements MSTeamsWebhookMessageSenderInterface
{
    private const REQUEST_SEND_MESSAGE_PARAM_NAME_BODY = 'body';
    private const REQUEST_SEND_MESSAGE_PARAM_NAME_HEADERS = 'headers';

    private const REQUEST_SEND_MESSAGE_PARAM_VALUE_HEADERS = [
        'Content-Type' => 'application/json',
    ];

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
        $this->validateMessage($message);
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

    /**
     * @throws EmptyMessageException
     */
    private function validateMessage(Message $message): void
    {
        if (empty($message->getText())) {
            throw new EmptyMessageException();
        }
    }

    private function requestSendMessage(Connector $connector, string $jsonMessageData): void
    {
        $this->client->post(
            $connector->getIncomingWebhookUrl(),
            [
                self::REQUEST_SEND_MESSAGE_PARAM_NAME_BODY => $jsonMessageData,
                self::REQUEST_SEND_MESSAGE_PARAM_NAME_HEADERS => self::REQUEST_SEND_MESSAGE_PARAM_VALUE_HEADERS,
            ]
        );
    }
}
