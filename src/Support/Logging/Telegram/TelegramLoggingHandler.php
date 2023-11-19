<?php

namespace Support\Logging\Telegram;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Services\Telegram\TelegramBotApiContract;

class TelegramLoggingHandler extends AbstractProcessingHandler
{
    protected string $chatId;
    protected string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        $this->chatId = $config['chat_id'];
        $this->token = $config['token'];

        $output = '%datetime% | (%level_name%) %message%';
        $dateFormat = 'j F, H:i';

        $formatter = new LineFormatter($output, $dateFormat);

        $this->setFormatter($formatter);

        parent::__construct($level);
    }

    protected function write(LogRecord $record): void
    {
        app(TelegramBotApiContract::class)::sendMessage($this->chatId, $this->token, $record->formatted);
    }
}
