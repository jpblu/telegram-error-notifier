[![Tests](https://github.com/jpblu/telegram-error-notifier/actions/workflows/tests.yml/badge.svg)](https://github.com/jpblu/telegram-error-notifier/actions/workflows/tests.yml)
![GitHub Release](https://img.shields.io/github/v/release/jpblu/telegram-error-notifier)
![Static Badge](https://img.shields.io/badge/PHP-%3E%3D%208.1-blue)
![Laravel Compatibility](https://img.shields.io/badge/Laravel-8.x%20|%209.x%20|%2010.x%20|%2011.x%20|%2012.x-blueviolet?logo=laravel&logoColor=white)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)


# Telegram Error Notifier

A PHP library for sending alert messages to a Telegram bot. Compatible with Laravel.

## Installation

You can install the package using Composer:

```bash
composer require jpblu/telegram-error-notifier
```

## Telegram Setup

1. Create a [Telegram bot](https://t.me/BotFather).
2. Get the bot token.
3. Create a private or public channel.
4. Add your bot as an **admin** of the channel.
5. Get the channel ID (prefix with `@` if it's a public channel or use the numeric ID for private ones).

> **Note**:
> Refer to [Telegram Bot documentations](https://core.telegram.org/bots/api) for further instructions.

## Usage (PHP projects)

```php
use TelegramNotifier\TelegramNotifier;

$notifier = new TelegramNotifier('TELEGRAM_BOT_TOKEN', 'TELEGRAM_CHAT_ID');
$response = $notifier->send('An error occurred in your service.');
```

## Usage (Laravel project)

This library automatically registers a Service Provider and Facade if used in a Laravel project.

### Configuration

1. Add environment variables to your `.env` file:
```env
TELEGRAM_BOT_TOKEN=your-bot-token
TELEGRAM_CHAT_ID=your-chat-id
```

2. Add to  `config/services.php`
```php
'telegram_notifier' => [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),
],
```

### Examples

#### Send an error message inside a `try/catch` block
```
use TelegramNotifier\TelegramNotifier;

public function store(Request $request)
{
    try {
        // Application logic
    } catch (\Throwable $e) {
        app(\TelegramNotifier::class)->send($e->getMessage());
        throw $e; // or handle the exception
    }
}
```

#### Notify errors inside a queued Job
```
use TelegramNotifier\TelegramNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessUserJob implements ShouldQueue
{
    public function handle()
    {
        try {
            // Background processing logic
        } catch (\Throwable $e) {
            app(\TelegramNotifier::class)->send("Job failed: " . $e->getMessage());
            throw $e;
        }
    }
}
```

#### Catch all unhandled exceptions in the global Exception Handler
Edit your `app/Exceptions/Handler.php` file:
```
use TelegramNotifier\TelegramNotifier;

public function report(Throwable $exception)
{
    parent::report($exception);

    if ($this->shouldReport($exception)) {
        app(\TelegramNotifier::class)->send($exception->getMessage());
    }
}
```

#### Manual notification
```
TelegramNotifier::send('User import completed successfully.');
```

## Returned Values

The `send()` method returns an **array** with the response from the Telegram API (or an error if applicable).

Example:

```php
[
    'ok' => true,
    'result' => [
        'message_id' => 123,
        'chat' => [...],
        'text' => 'Your message'
    ]
]
```

### Laravel Compatibility

This package has been tested and works with the following Laravel versions:

- Laravel 8.x
- Laravel 9.x (LTS)
- Laravel 10.x (LTS)
- Laravel 11.x
- Laravel 12.x

Laravel 5.5+ may also work, as this package uses automatic service provider registration via Composer.

### Acknowledgments
- [GuzzleHTTP](https://github.com/guzzle/guzzle) for client connection
- [PHPUnit](https://github.com/sebastianbergmann/phpunit/) for testing suite