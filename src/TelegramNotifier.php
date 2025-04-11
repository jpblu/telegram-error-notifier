<?php

namespace TelegramNotifier;

use GuzzleHttp\Client;

class TelegramNotifier
{
    protected string $botToken;
    protected string $chatId;
    protected Client $client;

    public function __construct(string $botToken, string $chatId)
    {
        $this->botToken = $botToken;
        $this->chatId = $chatId;
        $this->client = new Client([
            'base_uri' => "https://api.telegram.org/bot{$botToken}/"
        ]);
    }

    public function send(string $message): bool
    {
        try {
            $this->client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $this->chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown'
                ]
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
