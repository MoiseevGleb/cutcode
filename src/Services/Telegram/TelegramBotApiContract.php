<?php

namespace Services\Telegram;

interface TelegramBotApiContract
{
    public static function sendMessage(string $chatId, string $token, string $text): bool;
}
