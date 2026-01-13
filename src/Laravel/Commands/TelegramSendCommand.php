<?php

namespace TelegramNotifier\Laravel\Commands;

use Illuminate\Console\Command;
use TelegramNotifier\TelegramNotifier;

class TelegramSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:send {message : The message to send}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message to Telegram';

    /**
     * Execute the console command.
     *
     * @param TelegramNotifier $telegramNotifier
     * @return int
     */
    public function handle(TelegramNotifier $telegramNotifier): int
    {
        $message = $this->argument('message');

        $result = $telegramNotifier->send($message);

        if ($result['success']) {
            $this->info('Message sent successfully to Telegram!');
            return self::SUCCESS;
        } else {
            $this->error('Failed to send message: ' . $result['error']);
            return self::FAILURE;
        }
    }
}
