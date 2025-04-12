[![Tests](https://github.com/jpblu/telegram-error-notifier/actions/workflows/tests.yml/badge.svg)](https://github.com/jpblu/telegram-error-notifier/actions/workflows/tests.yml)
![GitHub Release](https://img.shields.io/github/v/release/jpblu/telegram-error-notifier)
![Static Badge](https://img.shields.io/badge/PHP-%3E%3D%208.1-blue)
![Laravel Compatibility](https://img.shields.io/badge/Laravel-8.x%20|%209.x%20|%2010.x%20|%2011.x-blueviolet?logo=laravel&logoColor=white)

# Telegram Error Notifier

A PHP library for sending alert messages to a Telegram bot. Compatible with Laravel.

## Install

```bash
composer require jpblu/telegram-error-notifier
```

## Usage (PHP projects)

```php
use TelegramNotifier\TelegramNotifier;

$notifier = new TelegramNotifier('BOT_TOKEN', 'CHAT_ID');
$notifier->send("Errore!");
```

## Usage (Laravel project)

### Configuration

1. Add environment variables to your `.env` file:
```
TELEGRAM_BOT_TOKEN=your-bot-token
TELEGRAM_CHAT_ID=your-chat-id
```

2. (Optional) Publish the config file:
```
php artisan vendor:publish --tag=telegram-notifier-config
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
        TelegramNotifier::notify($e->getMessage());
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
            TelegramNotifier::notify("Job failed: " . $e->getMessage());
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
        TelegramNotifier::notify($exception->getMessage());
    }
}
```

#### Manual notification
```
TelegramNotifier::notify('User import completed successfully.');
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