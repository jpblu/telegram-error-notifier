<?php

namespace TelegramNotifier\Laravel;

use Illuminate\Support\Facades\Facade;

class TelegramNotifierFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'telegram-notifier';
    }
}
