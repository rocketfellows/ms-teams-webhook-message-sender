<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;

/**
 * @group ms-teams-webhook-message-senders
 */
class MSTeamsWebhookMessageSenderTest extends TestCase
{
    private const EXPECTED_IMPLEMENTED_INTERFACES = [
        MSTeamsWebhookMessageSenderInterface::class,
    ];

    /**
     * @var MSTeamsWebhookMessageSenderInterface
     */
    private $sender;

    /**
     * @var Client
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(Client::class);

        $this->sender = new MSTeamsWebhookMessageSender(
            $this->client
        );
    }

    public function testSenderImplementsInterfaces(): void
    {
        foreach (self::EXPECTED_IMPLEMENTED_INTERFACES as $expectedImplementedInterface) {
            $this->assertInstanceOf($expectedImplementedInterface, $this->sender);
        }
    }

    /**
     * @dataProvider getInvalidConnectorProvidedData
     */
    public function testSendMessageNotExecutedCauseInvalidConnector(
        Connector $connector,
        Message $message,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendMessage($connector, $message);
    }

    public function getInvalidConnectorProvidedData(): array
    {
        return [
            'valid message, connector empty incoming webhook url' => [
                'connector' => new Connector(''),
                'message' => new Message('text'),
                'expectedExceptionClass' => EmptyIncomingWebhookUrlException::class,
            ],
            'valid message, connector incoming webhook invalid url' => [
                'connector' => new Connector('foo'),
                'message' => new Message('text'),
                'expectedExceptionClass' => InvalidIncomingWebhookUrlException::class,
            ],
        ];
    }
}
