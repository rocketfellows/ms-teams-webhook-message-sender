<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\models;

use PHPUnit\Framework\TestCase;

/**
 * @group ms-teams-webhook-message-sender-models
 */
class MessageTest extends TestCase
{
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
        ];
    }
}
