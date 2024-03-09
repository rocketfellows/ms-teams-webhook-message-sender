<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\senders;

use PHPUnit\Framework\TestCase;

/**
 * @group ms-teams-webhook-message-senders
 */
class MSTeamsWebhookMessageSenderTest extends TestCase
{
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
}
