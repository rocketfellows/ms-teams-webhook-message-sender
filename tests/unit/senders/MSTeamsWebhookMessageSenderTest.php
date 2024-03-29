<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\configs\Connector;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\EmptyIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\configs\InvalidIncomingWebhookUrlException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\message\EmptyMessageException;
use rocketfellows\MSTeamsWebhookMessageSender\exceptions\request\ConnectorException;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;
use Throwable;

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
     * @dataProvider getSuccessSendMessageProvidedData
     */
    public function testSuccessSendMessage(
        Connector $connector,
        Message $message,
        array $expectedRequestParams
    ): void {
        $this->client->expects($this->once())->method('post')->with(...$expectedRequestParams);

        $this->sender->sendMessage($connector, $message);
    }

    public function getSuccessSendMessageProvidedData(): array
    {
        return [
            'message text set, title empty' => [
                'connector' => new Connector('https://foo.com/'),
                'message' => new Message('text', ''),
                'expectedRequestParams' => [
                    'https://foo.com/',
                    [
                        'body' => '{"text":"text","title":""}',
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            ],
            'message text set, title not set' => [
                'connector' => new Connector('https://foo.com/'),
                'message' => new Message('text'),
                'expectedRequestParams' => [
                    'https://foo.com/',
                    [
                        'body' => '{"text":"text","title":null}',
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            ],
            'message text set, title set' => [
                'connector' => new Connector('https://foo.com/'),
                'message' => new Message('text', 'title'),
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
     * @dataProvider getHandlingRequestSendMessageExceptionsProvidedData
     */
    public function testHandleRequestSendMessageExceptions(
        Connector $connector,
        Message $message,
        array $expectedRequestParams,
        Throwable $thrownRequestSendMessageException,
        string $expectedExceptionClass
    ): void {
        $this->client
            ->expects($this->once())
            ->method('post')
            ->with(...$expectedRequestParams)
            ->willThrowException($thrownRequestSendMessageException);

        $this->expectException($expectedExceptionClass);

        $this->sender->sendMessage($connector, $message);
    }

    public function getHandlingRequestSendMessageExceptionsProvidedData(): array
    {
        return [
            'client throws GuzzleException' => [
                'connector' => new Connector('https://foo.com/'),
                'message' => new Message('text', 'title'),
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

    /**
     * @dataProvider getInvalidMessageProvidedData
     */
    public function testSendMessageNotExecutedCauseInvalidMessage(
        Connector $connector,
        Message $message,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendMessage($connector, $message);
    }

    public function getInvalidMessageProvidedData(): array
    {
        return [
            'valid connector, empty message text' => [
                'connector' => new Connector('https://foo.com/'),
                'message' => new Message(''),
                'expectedExceptionClass' => EmptyMessageException::class,
            ],
        ];
    }
}
