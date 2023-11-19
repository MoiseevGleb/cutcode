<?php

namespace Tests\Unit\Services\Telegram;

use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_send_message_success_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnTrue();

        $result = app(TelegramBotApiContract::class)::sendMessage('chatId', 'token', 'Test');

        $this->assertTrue($result);
    }

    public function it_send_message_fail_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnFalse();

        $result = app(TelegramBotApiContract::class)::sendMessage('chatId', 'token', 'Test');

        $this->assertFalse($result);
    }
}
