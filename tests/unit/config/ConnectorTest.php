<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\config;

use PHPUnit\Framework\TestCase;

/**
 * @group ms-teams-webhook-message-sender-configs
 */
class ConnectorTest extends TestCase
{
    /**
     * @dataProvider getInitConnectorProvidedData
     */
    public function testInitConnector(
        array $connectorData,
        array $expectedConnectorData
    ): void {
        $this->assertActualConnectorDataEqualsExpected(
            (new Connector($connectorData['incomingWebhookUrl'])),
            $expectedConnectorData
        );
    }

    public function getInitConnectorProvidedData(): array
    {
        return [];
    }

    private function assertActualConnectorDataEqualsExpected(Connector $connector, array $expectedConnectorData): void
    {
        $this->assertEquals($expectedConnectorData['incomingWebhookUrl'], $connector->getIncomingWebhookUrl());
    }
}
