<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\models;

use JsonSerializable;

class Message implements JsonSerializable
{
    private const PARAM_NAME_TEXT = 'text';
    private const PARAM_NAME_TITLE = 'title';

    private $text;
    private $title;

    public function __construct(
        string $text,
        ?string $title = null
    ) {
        $this->text = $text;
        $this->title = $title;
    }

    public static function create(
        string $text,
        ?string $title = null
    ): self {
        return (
            new self(
                $text,
                $title
            )
        );
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function jsonSerialize(): array
    {
        return [
            self::PARAM_NAME_TEXT => $this->getText(),
            self::PARAM_NAME_TITLE => $this->getTitle(),
        ];
    }
}
