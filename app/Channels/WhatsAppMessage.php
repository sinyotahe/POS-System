<?php

namespace App\Channels;

class WhatsAppMessage
{
    public function __construct(
        public string $to,
        public string $content,
    ) {}
}
