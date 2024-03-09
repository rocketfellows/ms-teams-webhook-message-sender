<?php

namespace rocketfellows\MSTeamsWebhookMessageSender\models;

class Message
{
    private $text;
    private $title;

    public function __construct(
        string $text,
        ?string $title = null
    ) {
        $this->text = $text;
        $this->title = $title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
