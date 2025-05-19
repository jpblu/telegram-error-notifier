<?php

namespace TelegramNotifier\Laravel;

use Illuminate\Support\ServiceProvider;
use TelegramNotifier\TelegramNotifier;

class TelegramNotifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('telegram-notifier', function ($app) {
            return new TelegramNotifier(
                config('services.telegram_notifier.bot_token'),
                config('services.telegram_notifier.chat_id')
            );
        });
    }

    public function boot() {

    }
}
