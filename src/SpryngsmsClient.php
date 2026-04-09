<?php

namespace Oscar\Spryngsms;

use Illuminate\Support\Facades\Log;
use Oscar\Spryngsms\Exceptions\CouldNotSendSmsNotification;
use Spryng\SpryngRestApi\Objects\Message;
use Spryng\SpryngRestApi\Spryng;

class SpryngsmsClient
{
    protected Spryng $client;

    public function __construct()
    {
        $this->client = new Spryng(config('spryngsms.token'));
    }

    /**
     * @throws CouldNotSendSmsNotification
     */
    public function send(SpryngsmsMessage $spryngsmsMessage): void
    {
        $message = app(Message::class);
        $message->setBody($spryngsmsMessage->body);
        $message->setRecipients($spryngsmsMessage->recipients ?? []);
        $message->setOriginator($spryngsmsMessage->originator ?? config('spryngsms.originator'));
        $message->setEncoding($spryngsmsMessage->encoding ?? config('spryngsms.encoding'));
        $message->setRoute($spryngsmsMessage->route ?? config('spryngsms.route'));
        $message->setReference($spryngsmsMessage->reference ?? config('spryngsms.reference'));
        $response = $this->client->message->create($message);

        if (! $response->wasSuccessful()) {
            Log::error($response->getRawBody());
        }
    }
}
