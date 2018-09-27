<?php

namespace Hindsight\Formatting\Formatters;

use Monolog\Logger;

class MonologToRFC5424SeverityFormatter extends HindsightFormatter
{
    protected $logLevels = [
        Logger::DEBUG     => LOG_DEBUG,
        Logger::INFO      => LOG_INFO,
        Logger::NOTICE    => LOG_NOTICE,
        Logger::WARNING   => LOG_WARNING,
        Logger::ERROR     => LOG_ERR,
        Logger::CRITICAL  => LOG_CRIT,
        Logger::ALERT     => LOG_ALERT,
        Logger::EMERGENCY => LOG_EMERG,
    ];

    function format(array $record): array
    {
         $record['level'] = $this->logLevels[$record['level']];
        return $record;
    }
}
