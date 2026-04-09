## Spryng SMS Notification Channel for Laravel

This repository provides a Laravel notification channel to send SMS via Spryng SMS.

### Installation

```bash
composer require oscar-team/spryngsms
```

You can also publish the config file with:

```bash
php artisan vendor:publish --tag=spryngsms-config
```

This is the content of the published config file. You can find more info on the config options at https://docs.spryngsms.com/#9-simple-http-api

```php
return [
    'token'      => env('SPRYNG_SMS_API_TOKEN'),
    'originator' => env('SPRYNG_SMS_FROM_NAME', 'Oscar'),
    'route'      => env('SPRYNG_SMS_ROUTE', 'business'),
    'encoding'   => env('SPRYNG_SMS_ENCODING', 'auto'),
    'reference'  => env('SPRYNG_SMS_REFERENCE'),
];
```

### Usage

You can use the channel in your `via()` method inside the notification:

```php
use Oscar\Spryngsms\SpryngsmsChannel;
use Oscar\Spryngsms\SpryngsmsMessage;

class BookingNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return [SpryngsmsChannel::class];
    }

    public function toSpryngsms(mixed $notifiable): SpryngsmsMessage|string
    {
        return new SpryngsmsMessage(
            body: 'Your booking has been confirmed.',
            recipients: ['31612345678'],
        );
    }
}
```

You can also return a plain string from `toSpryngsms`. In that case, the recipients will be resolved from the `routeNotificationForSpryngsms` method on your Notifiable model:

```php
public function routeNotificationForSpryngsms(): string|array
{
    return $this->phone_number;
}
```

#### `SpryngsmsMessage` parameters

| Parameter | Required | Description |
|---|---|---|
| `$body` | Yes | The message body |
| `$recipients` | No | Array of phone numbers. Resolved from the notifiable if not set. |
| `$originator` | No | Sender name or number. Falls back to config value. |
| `$encoding` | No | Character encoding (`plain`, `unicode`, or `auto`). Falls back to config value. |
| `$route` | No | Route ID. Falls back to config value. |
| `$reference` | No | A client reference. Falls back to config value. |

### Testing

```bash
composer test
```

### Code Style

```bash
composer format
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
