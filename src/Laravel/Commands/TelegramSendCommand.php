<?php

namespace TelegramNotifier\Laravel\Commands;

use TelegramNotifier\TelegramNotifier;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

if (class_exists(\Illuminate\Console\Command::class)) {
    class_alias(\Illuminate\Console\Command::class, __NAMESPACE__ . '\BaseCommand');
} else {
    class_alias(\Symfony\Component\Console\Command\Command::class, __NAMESPACE__ . '\BaseCommand');
}

class TelegramSendCommand extends BaseCommand
{
    protected $signature = 'telegram:send {message : The message to send}';
    protected $description = 'Send a Telegram message using TelegramNotifier';

    protected TelegramNotifier $telegramNotifier;

    protected ?InputInterface $inputMessage = null;
    protected ?OutputInterface $outputMessage = null;

    public function __construct(TelegramNotifier $telegramNotifier)
    {
        parent::__construct();
        $this->telegramNotifier = $telegramNotifier;
    }

    protected function configure(): void
    {
        $this
            ->setName('telegram:send')
            ->setDescription($this->description)
            ->addArgument('message', InputArgument::REQUIRED, 'The message to send');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->inputMessage = $input;
        $this->outputMessage = $output;

        return $this->handle();
    }

    public function handle(): int
    {
        $message = method_exists($this, 'argument')
            ? $this->argument('message')   // for Laravel
            : $this->inputMessage->getArgument('message'); // for PHP with Symfony

        $result = $this->telegramNotifier->send($message);

        if ($result['success']) {
            $this->sendInfo('Message sent successfully.');
            return defined('self::SUCCESS') ? self::SUCCESS : 0;
        }

        $error = $result['error'] ?? 'Unknown error';
        $this->sendError('Failed to send message: ' . $error);

        return defined('self::FAILURE') ? self::FAILURE : 1;
    }

    protected function sendInfo(string $text): void
    {
        if (method_exists($this, 'line')) {
            parent::line($text); // Laravel
        } elseif ($this->outputMessage) {
            $this->outputMessage->writeln("<info>$text</info>"); // Symfony
        }
    }

    protected function sendError(string $text): void
    {
        if (method_exists($this, 'error')) {
            parent::error($text); // Laravel
        } elseif ($this->outputMessage) {
            $this->outputMessage->writeln("<error>$text</error>"); // Symfony
        }
    }
}
