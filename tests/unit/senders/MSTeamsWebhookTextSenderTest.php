<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookTextSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;

/**
 * @group ms-teams-webhook-message-senders
 */
class MSTeamsWebhookTextSenderTest extends TestCase
{
    private const EXPECTED_IMPLEMENTED_INTERFACES = [
        MSTeamsWebhookTextSenderInterface::class,
    ];

    /**
     * @var MSTeamsWebhookTextSenderInterface
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
     * @dataProvider getInvalidTextProvidedData
     */
    public function testSendTextNotExecutedCauseInvalidText(
        Connector $connector,
        string $text,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendText($connector, $text);
    }

    public function getInvalidTextProvidedData(): array
    {
        return [
            'valid connector, empty text' => [
                'connector' => new Connector('https://foo.com/'),
                'text' => '',
                'expectedExceptionClass' => EmptyMessageException::class,
            ],
        ];
    }
}
