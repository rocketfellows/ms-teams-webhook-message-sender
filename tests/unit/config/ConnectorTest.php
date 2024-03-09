<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\tests\unit\config;

use PHPUnit\Framework\TestCase;
use rocketfellows\MSTeamsWebhookMessageSender\config\Connector;

/**
 * @group ms-teams-webhook-message-sender-configs
 */
class ConnectorTest extends TestCase
{
    /**
     * @dataProvider getInitConnectorProvidedData
     */
    public function testCreateConnector(
        array $connectorData,
        array $expectedConnectorData
    ): void {
        $this->assertActualConnectorDataEqualsExpected(
            Connector::create($connectorData['incomingWebhookUrl']),
            $expectedConnectorData
        );
    }

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
        return [
            'incoming webhook url not empty' => [
                'connectorData' => [
                    'incomingWebhookUrl' => 'incomingWebhookUrl',
                ],
                'expectedConnectorData' => [
                    'incomingWebhookUrl' => 'incomingWebhookUrl',
                ],
            ],
            'incoming webhook url empty' => [
                'connectorData' => [
                    'incomingWebhookUrl' => '',
                ],
                'expectedConnectorData' => [
                    'incomingWebhookUrl' => '',
                ],
            ],
        ];
    }

    private function assertActualConnectorDataEqualsExpected(Connector $connector, array $expectedConnectorData): void
    {
        $this->assertEquals($expectedConnectorData['incomingWebhookUrl'], $connector->getIncomingWebhookUrl());
    }
}
