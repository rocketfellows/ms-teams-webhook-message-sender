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

`rocketfellows\MSTeamsWebhookMessageSender\configs\Connector` - a class that encapsulates the connection configuration for sending a message.

Class description:
- `incomingWebhookUrl` - **_string_** - link to a webhook (connector) for sending a message;
- `create` - **_static function_** - static factory function that returns a value of type `Connector`;
- `getIncomingWebhookUrl` - _**function**_ - getter that returns the value of the `incomingWebhookUrl` attribute.

#### Message.

`rocketfellows\MSTeamsWebhookMessageSender\models\Message` - a class that encapsulates message data to be sent via a webhook and implements the `JsonSerializable` interface.

Class description:
- `text` - _**string**_ - message text to send;
- `title` - _**string | null**_ - message title to send;
- `create` - **_static function_** - static factory function returning a value of type `Message`;
- `convertToJson` - **_function_** - function, returns a representation of a `Message` type value as a json string;
- `jsonSerialize` - **_function_** - implementation of the `JsonSerializable` interface;
- `getText` - **_function_** - getter returning `text` attribute value;
- `getTitle` - **_function_** - getter returning `title` attribute value.

### Interfaces.

#### MSTeamsWebhookMessageSenderInterface.

`rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookMessageSenderInterface` - interface for sending a message via a webhook uses a `Connector` type value as a connection, and a `Message` type value as a message.

```php
public function sendMessage(Connector $connector, Message $message): void;
```

Interface exceptions:
- `EmptyIncomingWebhookUrlException` - thrown if the link to the webhook is an empty string.
- `InvalidIncomingWebhookUrlException` - thrown if the link to the webhook is not valid (for example, the link is not an url).
- `EmptyMessageException` - thrown if the message text is an empty string.
- `ConnectorException` - thrown if an error occurred when sending a message (for example, if the HTTP response code is not 200).

##### Usage examples.

Send message with title:

```php
$sender->sendMessage(Connector::create(INCOMING_WEBHOOK_URL), Message::create('Hello world!', 'Hello!'));
```

Result:

![Send message with title result](/readme/src/img.png)

Send message without title:

```php
$sender->sendMessage(Connector::create(INCOMING_WEBHOOK_URL), Message::create('Hello world!'));
```

Result:

![Send message without title result](/readme/src/img_0.png)

#### MSTeamsWebhookTextSenderInterface.

`rocketfellows\MSTeamsWebhookMessageSender\MSTeamsWebhookTextSenderInterface` - interface for sending a message via a webhook uses a value of type `Connector` as a connection, and a value of type string (message text) as a message.

```php
public function sendText(Connector $connector, string $text): void;
```

Interface exceptions:
- `EmptyIncomingWebhookUrlException` - thrown if the link to the webhook is an empty string.
- `InvalidIncomingWebhookUrlException` - thrown if the link to the webhook is not valid (for example, the link is not an url).
- `EmptyMessageException` - thrown if the message text is an empty string.
- `ConnectorException` - thrown if an error occurred when sending a message (for example, if the HTTP response code is not 200).

##### Usage examples.

Send text:

```php
$sender->sendText(Connector::create(INCOMING_WEBHOOK_URL), 'Hello world!');
```

Result:

![Send text result](/readme/src/img_1.png)

