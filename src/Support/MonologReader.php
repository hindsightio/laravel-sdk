<?php

namespace Hindsight\Support;

use Hindsight\HindsightMonologHandler;
use Monolog\Logger;

class MonologReader
{
    public static function retrieveHindsightHandler(Logger $logger): ?HindsightMonologHandler
    {
        return collect($logger->getHandlers())->first(function ($handler) {
            return $handler instanceof HindsightMonologHandler;
        });
    }
}
