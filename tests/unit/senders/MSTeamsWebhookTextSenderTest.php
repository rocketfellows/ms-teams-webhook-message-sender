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
use rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookTextSenderInterface;
use rocketfellows\MSTeamsWebhookMessageSender\senders\MSTeamsWebhookMessageSender;
use Throwable;

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
     * @dataProvider getSuccessSendTextProvidedData
     */
    public function testSuccessSendText(
        Connector $connector,
        string $text,
        array $expectedRequestParams
    ): void {
        $this->client->expects($this->once())->method('post')->with(...$expectedRequestParams);

        $this->sender->sendText($connector, $text);
    }

    public function getSuccessSendTextProvidedData(): array
    {
        return [
            'text not empty' => [
                'connector' => new Connector('https://foo.com/'),
                'text' => 'text',
                'expectedRequestParams' => [
                    'https://foo.com/',
                    [
                        'body' => '{"text":"text","title":null}',
                        'headers' => ['Content-Type' => 'application/json'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getHandlingRequestSendTextExceptionsProvidedData
     */
    public function testHandleRequestSendTextExceptions(
        Connector $connector,
        string $text,
        array $expectedRequestParams,
        Throwable $thrownRequestSendTextException,
        string $expectedExceptionClass
    ): void {
        $this->client
            ->expects($this->once())
            ->method('post')
            ->with(...$expectedRequestParams)
            ->willThrowException($thrownRequestSendTextException);

        $this->expectException($expectedExceptionClass);

        $this->sender->sendText($connector, $text);
    }

    public function getHandlingRequestSendTextExceptionsProvidedData(): array
    {
        return [
            'client throws GuzzleException' => [
                'connector' => new Connector('https://foo.com/'),
                'text' => 'text',
                'expectedRequestParams' => [
                    'https://foo.com/',
                    [
                        'body' => '{"text":"text","title":null}',
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
    public function testSendTextNotExecutedCauseInvalidConnector(
        Connector $connector,
        string $text,
        string $expectedExceptionClass
    ): void {
        $this->expectException($expectedExceptionClass);

        $this->sender->sendText($connector, $text);
    }

    public function getInvalidConnectorProvidedData(): array
    {
        return [
            'valid text, connector empty incoming webhook url' => [
                'connector' => new Connector(''),
                'text' => 'text',
                'expectedExceptionClass' => EmptyIncomingWebhookUrlException::class,
            ],
            'valid text, connector incoming webhook invalid url' => [
                'connector' => new Connector('foo'),
                'text' => 'text',
                'expectedExceptionClass' => InvalidIncomingWebhookUrlException::class,
            ],
        ];
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
