<?php

namespace TelegramNotifier\Laravel;

use Illuminate\Support\ServiceProvider;
use TelegramNotifier\TelegramNotifier;

class TelegramNotifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TelegramNotifier::class, function ($app) {
            $config = config('services.telegram_notifier');
            return new TelegramNotifier(
                $config['bot_token'],
                $config['chat_id']
            );
        });
    }

    public function boot() {

    }
}
