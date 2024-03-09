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
}
