<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use TelegramNotifier\Laravel\Commands\TelegramSendCommand;
use TelegramNotifier\TelegramNotifier;
use Symfony\Component\Console\Tester\CommandTester;

class TelegramSendCommandTest extends TestCase
{
    #[AllowMockObjectsWithoutExpectations]
    public function testCommandSendMessageSuccessfully()
    {
        // Create a mock TelegramNotifier that returns success
        $mockNotifier = $this->createMock(TelegramNotifier::class);
        $mockNotifier->method('send')->willReturn([
            'success' => true,
            'response' => [
                'ok' => true,
                'result' => [
                    'message_id' => 123,
                    'text' => 'Test message',
                    'chat' => ['id' => '123456']
                ]
            ]
        ]);

        // Create and test the command
        $command = new TelegramSendCommand($mockNotifier);
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(['message' => 'Test message']);

        // Assertions
        $this->assertEquals(0, $exitCode); // SUCCESS = 0
        $this->assertStringContainsString('Message sent successfully', $commandTester->getDisplay());
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testCommandHandlesError()
    {
        // Create a mock TelegramNotifier that returns error
        $mockNotifier = $this->createMock(TelegramNotifier::class);
        $mockNotifier->method('send')->willReturn([
            'success' => false,
            'error' => 'Unauthorized: Invalid bot token'
        ]);

        // Create and test the command
        $command = new TelegramSendCommand($mockNotifier);
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(['message' => 'Test message']);

        // Assertions
        $this->assertEquals(1, $exitCode); // FAILURE = 1
        $this->assertStringContainsString('Failed to send message', $commandTester->getDisplay());
        $this->assertStringContainsString('Unauthorized: Invalid bot token', $commandTester->getDisplay());
    }
}
