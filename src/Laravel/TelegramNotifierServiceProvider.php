<?php

namespace TelegramNotifier\Laravel;

use Illuminate\Support\ServiceProvider;
use TelegramNotifier\TelegramNotifier;
use TelegramNotifier\Laravel\Commands\TelegramSendCommand;

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

        $this->app->alias(TelegramNotifier::class, 'telegram-notifier');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TelegramSendCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [TelegramSendCommand::class];
    }
}
