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
    public function testSendJsonMessageNotExecutedCauseInvalidJsonMessage(
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
            'valid connector, json message is empty string' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is invalid json string' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{text:text, title: title}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with wrong quotes' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => "{'text':'text'}",
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string without required escape sequence' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text":"John says "Hello!""}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with currency sign in number' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": $1.00}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with expression in number' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": 99.00 * 0.15}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with extra comma (,) in array' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": ["Hello", 3.14, true, ]}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with closing bracket is wrong in array' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": ["Hello", 3.14, true}}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with name value pair in array' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": ["Hello", 3.14, true, "name": "Joe"]}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with extra comma (,) in object' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": {"name": "Joe","age":null,}}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with closing bracket is wrong in object' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": {"name": "Joe", "age": null]}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with missing value in name value pair in object' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": {"name": "Joe", "age": }}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
            'valid connector, json message is json string with missing : after name in object' => [
                'connector' => new Connector('https://foo.com/'),
                'jsonMessage' => '{"text": {"name": "Joe", "age" }}',
                'expectedExceptionClass' => InvalidJsonMessageException::class,
            ],
        ];
    }
}
