<?php

use PHPUnit\Framework\TestCase;
use TelegramNotifier\TelegramNotifier;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TelegramNotifierTest extends TestCase
{
    public function testSendReturnsTrueOnSuccess()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['ok' => true]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack, 'base_uri' => 'https://api.telegram.org/botTEST_TOKEN/']);

        $notifier = new TelegramNotifier('TEST_TOKEN', 'TEST_CHAT');
        $reflection = new \ReflectionClass($notifier);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($notifier, $client);

        $this->assertTrue($notifier->send('Test message'));
    }

    public function testSendReturnsFalseOnFailure()
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

        $this->assertFalse($notifier->send('Test message'));
    }
}
