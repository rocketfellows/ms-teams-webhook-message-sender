<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageDataException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\request\ConnectorException;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookArrayMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;
use Throwable;

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
     * @dataProvider getSuccessSendMessageFromArrayProvidedData
     */
    public function testSuccessSendMessageFromArray(
        Connector $connector,
        array $messageData,
        array $expectedRequestParams
    ): void {
        $this->client->expects($this->once())->method('post')->with(...$expectedRequestParams);

        $this->sender->sendMessageFromArray($connector, $messageData);
    }

    public function getSuccessSendMessageFromArrayProvidedData(): array
    {
        return [
            'message data valid, connector valid' => [
                'connector' => new Connector('https://foo.com/'),
                'messageData' => ['text' => 'text', 'title' => 'title',],
                'expectedRequestParams' => [
                    'https://foo.com/',
                    [
                        'body' => '{"text":"text","title":"title"}',
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getHandlingRequestSendMessageFromArrayExceptionsProvidedData
     */
    public function testHandleRequestSendMessageFromArrayExceptions(
        Connector $connector,
        array $messageData,
        array $expectedRequestParams,
        Throwable $thrownRequestSendMessageFromArrayException,
        string $expectedExceptionClass
    ): void {
        $this->client
            ->expects($this->once())
            ->method('post')
            ->with(...$expectedRequestParams)
            ->willThrowException($thrownRequestSendMessageFromArrayException);

        $this->expectException($expectedExceptionClass);

        $this->sender->sendMessageFromArray($connector, $messageData);
    }

    public function getHandlingRequestSendMessageFromArrayExceptionsProvidedData(): array
    {
        return [
            'client throws GuzzleException' => [
                'connector' => new Connector('https://foo.com/'),
                'messageData' => ['text' => 'text', 'title' => 'title',],
                'expectedRequestParams' => [
                    'https://foo.com/',
                    [
                        'body' => '{"text":"text","title":"title"}',
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
                'thrownRequestSendMessageException' => $this->createMock(GuzzleException::class),
                'expectedExceptionClass' => ConnectorException::class,
            ],
        ];
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

    public function getInvalidConnectorProvidedData(): array
    {
        return [
            'valid message data, connector empty incoming webhook url' => [
                'connector' => new Connector(''),
                'messageData' => ['text' => 'text', 'title' => 'title',],
                'expectedExceptionClass' => EmptyIncomingWebhookUrlException::class,
            ],
            'valid message data, connector incoming webhook invalid url' => [
                'connector' => new Connector('foo'),
                'messageData' => ['text' => 'text', 'title' => 'title',],
                'expectedExceptionClass' => InvalidIncomingWebhookUrlException::class,
            ],
        ];
    }

    /**
     * @dataProvider getInvalidMessageArrayProvidedData
     */
    public function testSendMessageFromArrayNotExecutedCauseInvalidMessageData(
        Connector $connector,
        array $messageData,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendMessageFromArray($connector, $messageData);
    }

    public function getInvalidMessageArrayProvidedData(): array
    {
        return [
            'valid connector, message data is empty' => [
                'connector' => new Connector('https://foo.com/'),
                'messageData' => [],
                'expectedExceptionClass' => EmptyMessageDataException::class,
            ],
        ];
    }
}
