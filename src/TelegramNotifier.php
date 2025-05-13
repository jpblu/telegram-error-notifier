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

    public function send(string $message): array
    {
        try {
            $response = $this->client->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $this->chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ]
            ]);

            $body = json_decode((string) $response->getBody(), true);
            return [
                'success' => true,
                'response' => $body
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
