<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\models;

use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\models\Message;

/**
 * @group ms-teams-webhook-message-sender-models
 */
class MessageTest extends TestCase
{
    /**
     * @dataProvider getInitMessageProvidedData
     */
    public function testCreate(
        array $messageData,
        array $expectedMessageData
    ): void {
        $this->assertActualMessageDataEqualsExpected(
            Message::create($messageData['text'], $messageData['title']),
            $expectedMessageData
        );
    }

    /**
     * @dataProvider getInitMessageProvidedData
     */
    public function testInitMessage(
        array $messageData,
        array $expectedMessageData
    ): void {
        $this->assertActualMessageDataEqualsExpected(
            (new Message($messageData['text'], $messageData['title'])),
            $expectedMessageData
        );
    }

    public function getInitMessageProvidedData(): array
    {
        return [
            'text not empty, title not empty' => [
                'messageData' => [
                    'text' => 'text',
                    'title' => 'title',
                ],
                'expectedMessageData' => [
                    'text' => 'text',
                    'title' => 'title',
                ],
            ],
            'text empty, title empty' => [
                'messageData' => [
                    'text' => '',
                    'title' => '',
                ],
                'expectedMessageData' => [
                    'text' => '',
                    'title' => '',
                ],
            ],
            'text not empty, title not set' => [
                'messageData' => [
                    'text' => 'text',
                    'title' => null,
                ],
                'expectedMessageData' => [
                    'text' => 'text',
                    'title' => null,
                ],
            ],
            'text empty, title not set' => [
                'messageData' => [
                    'text' => '',
                    'title' => null,
                ],
                'expectedMessageData' => [
                    'text' => '',
                    'title' => null,
                ],
            ],
        ];
    }

    private function assertActualMessageDataEqualsExpected(Message $actualMessage, array $expectedMessageData): void
    {
        $this->assertEquals($expectedMessageData['text'], $actualMessage->getText());
        $this->assertEquals($expectedMessageData['title'], $actualMessage->getTitle());
    }
}
