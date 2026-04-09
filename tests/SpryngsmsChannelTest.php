<?php

use Illuminate\Notifications\Notification;
use Mockery\MockInterface;
use Oscar\Spryngsms\SpryngsmsChannel;
use Oscar\Spryngsms\SpryngsmsClient;
use Oscar\Spryngsms\SpryngsmsMessage;

function mockNotification(mixed $notifiable, string|SpryngsmsMessage $message): void
{
    test()->instance(
        Notification::class,
        Mockery::mock(Notification::class,
            fn (MockInterface $mock) => $mock->shouldReceive('toSpryngsms')
                ->once()
                ->with($notifiable)
                ->andReturn($message)
        )
    );
}

function mockClient(mixed $expectation): void
{
    test()->instance(
        SpryngsmsClient::class,
        Mockery::mock(
            SpryngsmsClient::class,
            fn (MockInterface $mock) => $mock->shouldReceive('send')
                ->once()
                ->with($expectation)
        )
    );
}

function sendNotification(mixed $notifiable): void
{
    $channel = new SpryngsmsChannel(app(SpryngsmsClient::class));
    $channel->send($notifiable, app(Notification::class));
}

it('sends sms with a message object', function () {
    $message = new SpryngsmsMessage('message', [1234567]);
    $notifiable = new stdClass;

    mockNotification($notifiable, $message);
    mockClient($message);
    sendNotification($notifiable);
});

it('converts a string message using the notifiable phone number', function () {
    $notifiable = new class
    {
        public int $phoneNumber = 12345678;

        public function routeNotificationForSpryngsms(): int
        {
            return $this->phoneNumber;
        }
    };

    mockNotification($notifiable, 'message');
    mockClient(Mockery::on(fn ($arg) => $arg == new SpryngsmsMessage('message', [$notifiable->phoneNumber])));
    sendNotification($notifiable);
});

it('converts a string message using a phone number array', function () {
    $notifiable = new class
    {
        public array $phoneNumbers = [12345678, 87654321];

        public function routeNotificationForSpryngsms(): array
        {
            return $this->phoneNumbers;
        }
    };

    mockNotification($notifiable, 'message');
    mockClient(Mockery::on(fn ($arg) => $arg == new SpryngsmsMessage('message', $notifiable->phoneNumbers)));
    sendNotification($notifiable);
});

it('does not send when recipients are empty', function () {
    $message = new SpryngsmsMessage('message', []);
    $notifiable = new stdClass;

    mockNotification($notifiable, $message);

    test()->instance(
        SpryngsmsClient::class,
        Mockery::mock(
            SpryngsmsClient::class,
            fn (MockInterface $mock) => $mock->shouldNotReceive('send')
        )
    );

    sendNotification($notifiable);
});

it('does not send when notifiable has no route and message has no recipients', function () {
    $notifiable = new stdClass;

    mockNotification($notifiable, 'message');

    test()->instance(
        SpryngsmsClient::class,
        Mockery::mock(
            SpryngsmsClient::class,
            fn (MockInterface $mock) => $mock->shouldNotReceive('send')
        )
    );

    sendNotification($notifiable);
});
