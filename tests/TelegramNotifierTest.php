<?php

use PHPUnit\Framework\TestCase;
use TelegramNotifier\TelegramNotifier;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TelegramNotifierTest extends TestCase
{
    public function testSendReturnsSuccessOnValidResponse()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'ok' => true,
                'result' => [
                    'message_id' => 123,
                    'text' => 'Test message'
                ]
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack, 'base_uri' => 'https://api.telegram.org/botTEST_TOKEN/']);

        $notifier = new TelegramNotifier('TEST_TOKEN', 'TEST_CHAT');
        $reflection = new \ReflectionClass($notifier);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($notifier, $client);

        $result = $notifier->send('Test message');

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('response', $result);
        $this->assertEquals('Test message', $result['response']['result']['text']);
    }

    public function testSendReturnsErrorOnException()
    {
        $mock = new MockHandler([
            new Response(500)
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack, 'base_uri' => 'https://api.telegram.org/botTEST_TOKEN/']);

        $notifier = new TelegramNotifier('TEST_TOKEN', 'TEST_CHAT');
        $reflection = new \ReflectionClass($notifier);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($notifier, $client);

        $result = $notifier->send('Test message');

        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
        $this->assertIsString($result['error']);
    }
}
