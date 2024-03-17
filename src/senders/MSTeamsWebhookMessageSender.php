<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\senders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageDataException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\InvalidJsonMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\request\ConnectorException;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookArrayMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookJsonMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookTextSenderInterface;

class MSTeamsWebhookMessageSender implements
    MSTeamsWebhookMessageSenderInterface,
    MSTeamsWebhookTextSenderInterface,
    MSTeamsWebhookJsonMessageSenderInterface,
    MSTeamsWebhookArrayMessageSenderInterface
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

    public function sendText(
        Connector $connector,
        string $text
    ): void {
        $this->sendMessage(
            $connector,
            Message::create($text)
        );
    }

    public function sendMessage(
        Connector $connector,
        Message $message
    ): void {
        $this
            ->validateConnector($connector)
            ->validateMessage($message)
            ->requestSendMessage(
                $connector,
                $message->convertToJson()
            );
    }

    public function sendMessageFromArray(
        Connector $connector,
        array $messageData
    ): void {
        $this->sendJsonMessage($connector, json_encode($messageData));
    }

    public function sendJsonMessage(
        Connector $connector,
        string $jsonMessage
    ): void {
        $this
            ->validateConnector($connector)
            ->validateJsonMessage($jsonMessage)
            ->requestSendMessage($connector, $jsonMessage);
    }

    /**
     * @throws EmptyIncomingWebhookUrlException
     * @throws InvalidIncomingWebhookUrlException
     */
    private function validateConnector(Connector $connector): self
    {
        if (empty($connector->getIncomingWebhookUrl())) {
            throw new EmptyIncomingWebhookUrlException();
        }

        if (!filter_var($connector->getIncomingWebhookUrl(), FILTER_VALIDATE_URL)) {
            throw new InvalidIncomingWebhookUrlException();
        }

        return $this;
    }

    /**
     * @throws EmptyMessageException
     */
    private function validateMessage(Message $message): self
    {
        if (empty($message->getText())) {
            throw new EmptyMessageException();
        }

        return $this;
    }

    /**
     * @throws InvalidJsonMessageException
     * @throws EmptyMessageDataException
     */
    private function validateJsonMessage(string $jsonMessage): self
    {
        $messageData = json_decode($jsonMessage, true);

        if (!is_array($messageData)) {
            throw new InvalidJsonMessageException();
        }

        if (empty($messageData)) {
            throw new EmptyMessageDataException();
        }

        return $this;
    }

    /**
     * @throws ConnectorException
     */
    private function requestSendMessage(
        Connector $connector,
        string $jsonMessageData
    ): void {
        try {
            $this->client->post(
                $connector->getIncomingWebhookUrl(),
                [
                    self::REQUEST_SEND_MESSAGE_PARAM_NAME_BODY => $jsonMessageData,
                    self::REQUEST_SEND_MESSAGE_PARAM_NAME_HEADERS => self::REQUEST_SEND_MESSAGE_PARAM_VALUE_HEADERS,
                ]
            );
        } catch (GuzzleException $exception) {
            throw new ConnectorException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
