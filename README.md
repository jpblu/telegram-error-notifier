[![Run Tests](https://github.com/jpblu/telegram-error-notifier/actions/workflows/tests.yml/badge.svg)](https://github.com/tuonome/telegram-error-notifier/actions/workflows/tests.yml)
# Telegram Error Notifier

A PHP library to send messages to a Telegram bot in case of error. Compatible with Laravel.

## Install

```bash
composer require jpblu/telegram-error-notifier
```

## Use (PHP projects)

```php
use TelegramNotifier\TelegramNotifier;

$notifier = new TelegramNotifier('BOT_TOKEN', 'CHAT_ID');
$notifier->send("Errore!");
```

## Use (Laravel project)

1. add to `.env`:
```
TELEGRAM_BOT_TOKEN=your-bot-token
TELEGRAM_CHAT_ID=your-chat-id
```

2. Add in `Handler.php`:

```php
public function report(Throwable $exception)
{
    parent::report($exception);

    if (app()->bound(\TelegramNotifier\TelegramNotifier::class)) {
        app(\TelegramNotifier\TelegramNotifier::class)
            ->send("*Error:* `" . get_class($exception) . "`\n*Message:* " . $exception->getMessage());
    }
}
```
