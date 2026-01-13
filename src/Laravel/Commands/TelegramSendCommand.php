<?php

namespace TelegramNotifier\Laravel\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TelegramNotifier\TelegramNotifier;

class TelegramSendCommand extends Command
{
    protected static $defaultName = 'telegram:send';
    protected static $defaultDescription = 'Send a message to Telegram';

    private TelegramNotifier $telegramNotifier;

    public function __construct(TelegramNotifier $telegramNotifier)
    {
        parent::__construct();
        $this->telegramNotifier = $telegramNotifier;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('message', InputArgument::REQUIRED, 'The message to send');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = $input->getArgument('message');

        $result = $this->telegramNotifier->send($message);

        if ($result['success']) {
            $output->writeln('<info>Message sent successfully to Telegram!</info>');
            return self::SUCCESS;
        } else {
            $output->writeln('<error>Failed to send message: ' . $result['error'] . '</error>');
            return self::FAILURE;
        }
    }
}
