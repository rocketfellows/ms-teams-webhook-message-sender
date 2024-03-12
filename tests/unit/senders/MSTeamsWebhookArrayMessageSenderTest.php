<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookArrayMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;

/**
 * @group ms-teams-webhook-message-senders
 */
class MSTeamsWebhookArrayMessageSenderTest extends TestCase
{
    private const EXPECTED_IMPLEMENTED_INTERFACES = [
        MSTeamsWebhookArrayMessageSenderInterface::class,
    ];

    /**
     * @var MSTeamsWebhookArrayMessageSenderInterface
     */
    private $sender;

    /**
     * @var Client|MockObject
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
    public function testSendMessageFromArrayNotExecutedCauseInvalidConnector(
        Connector $connector,
        array $messageData,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendMessageFromArray($connector, $messageData);
    }
}
