<?php

namespace TelegramNotifier\Laravel;

use Illuminate\Support\ServiceProvider;
use TelegramNotifier\TelegramNotifier;

class TelegramNotifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TelegramNotifier::class, function ($app) {
            return new TelegramNotifier(
                config('telegram_notifier.bot_token'),
                config('telegram_notifier.chat_id')
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/telegram_notifier.php' => config_path('telegram_notifier.php'),
        ], 'config');
    }
}
