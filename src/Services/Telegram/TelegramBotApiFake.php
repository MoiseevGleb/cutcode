<?php

namespace Services\Telegram;

class TelegramBotApiFake extends TelegramBotApi
{
    protected static bool $success = true;

    public function returnTrue(): static
    {
        static::$success = true;

        return $this;
    }

    public function returnFalse(): static
    {
        static::$success = false;

        return $this;
    }

    public static function sendMessage(string $chatId, string $token, string $text): bool
    {
        return static::$success;
    }
}
