<?php

namespace Oscar\Spryngsms;

class SpryngsmsMessage
{
    public function __construct(
        public string $body,
        public array $recipients = [],
        public ?string $originator = null,
        public ?string $encoding = null,
        public ?string $route = null,
        public ?string $reference = null,
    ) {}
}
