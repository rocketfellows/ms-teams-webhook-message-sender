<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookJsonMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;

/**
 * @group ms-teams-webhook-message-senders
 */
class MSTeamsWebhookJsonMessageSenderTest extends TestCase
{
    private const EXPECTED_IMPLEMENTED_INTERFACES = [
        MSTeamsWebhookJsonMessageSenderInterface::class,
    ];

    /**
     * @var MSTeamsWebhookJsonMessageSenderInterface
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
     * @dataProvider getInvalidJsonMessageProvidedData
     */
    public function testSendJsonMessageNotExecutedCauseInvalidText(
        Connector $connector,
        string $jsonMessage,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendJsonMessage($connector, $jsonMessage);
    }

    public function getInvalidJsonMessageProvidedData(): array
    {
        return [
            [],
        ];
    }
}
