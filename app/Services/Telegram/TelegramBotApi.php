<?php

namespace App\Services\Telegram;

use App\Logging\Telegram\Exceptions\TelegramApiException;
use Illuminate\Support\Facades\Http;
use TheSeer\Tokenizer\Exception;
use Throwable;

class TelegramBotApi
{
    public const HOST = "https://api.telegram.org/bot";

    /**
     * @throws Exception
     */
    public static function sendMessage(string $chatId, string $token, string $text): bool
    {
        try {
            $response = Http::get(self::HOST.$token.'/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
            ])->throw()->json();

            return $response['ok'] ?? false;
        } catch (Throwable $exception) {
            report(new TelegramApiException($exception->getMessage()));

            return false;
        }
    }
}
