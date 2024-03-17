# Microsoft Teams webhook message sender.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
![PHPStan Badge](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat)
![Code Coverage Badge](./badge.svg)

This package is designed for sending messages to Microsoft Teams (MS Teams) channels using webhooks (incoming webhook or connector).
For more information about sending messages to MS Teams channels using web hooks see https://learn.microsoft.com/en-us/microsoftteams/platform/webhooks-and-connectors/how-to/connectors-using?tabs=cURL.

## Installation.

```shell
composer require rocketfellows/ms-teams-webhook-message-sender
```

## Dependencies.

Current implementation dependencies:
- guzzle client - https://github.com/guzzle/guzzle - using for http request to webhook.

## MS Teams webhook message sender description.

### Basic package types.

#### Connector.

`rocketfellows\MSTeamsWebhookMessageSender\configs\Connector` - a class that encapsulates the connection data for sending a message.

Class description:
- `incomingWebhookUrl` - **_string_** - link to a webhook (connector) for sending a message;
- `create` - **_static function_** - static factory function that returns a value of type `Connector`;
- `getIncomingWebhookUrl` - _**function**_ - getter that returns the value of the `incomingWebhookUrl` attribute.
