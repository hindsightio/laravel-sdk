<?php

namespace Hindsight\Formatting\Formatters;

use DateTime;
use DateTimeImmutable;

class DatetimeToMillisecondsFormatter extends HindsightFormatter
{

    function format(array $record): array
    {
        if (!$this->shouldProcess($record)) return $record;

        // check for a php <7.2 bug:
        // https://bugs.php.net/bug.php?id=74753
        if (version_compare(PHP_VERSION, '7.2.0', '<') &&
            $record['datetime']->format('v') == 1000
        ) {
            $record['timestamp'] = (int)($record['datetime']->format('U') . '999');
        } else {
            $record['timestamp'] = (int)$record['datetime']->format('Uv');
        }

        unset($record['datetime']);

        return $record;
    }

    protected function shouldProcess(array $record)
    {
        return isset($record['datetime']) && (
                $record['datetime'] instanceof DateTime ||
                $record['datetime'] instanceof DateTimeImmutable
            );
    }
}
