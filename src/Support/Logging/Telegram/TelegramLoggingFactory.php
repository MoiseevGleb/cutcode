<?php

namespace Support\Logging\Telegram;

use DateTimeZone;
use Monolog\Logger;

class TelegramLoggingFactory
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('telegram', [new TelegramLoggingHandler($config)]);

        $logger->setTimezone(new DateTimeZone('Europe/Moscow'));

        return $logger;
    }
}
