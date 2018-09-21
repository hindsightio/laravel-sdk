<?php

namespace Hindsight\Support;

use Hindsight\HindsightMonologHandler;
use Monolog\Logger;

class MonologReader
{
    public static function retrieveHindsightHandler(Logger $logger, bool $popOffStack = false): ?HindsightMonologHandler
    {
        $monologHandler = collect($logger->getHandlers())->first(function ($handler) {
            return $handler instanceof HindsightMonologHandler;
        });

        // Pop the HS handler off the handler stack
        if ($popOffStack) {
            $logger->setHandlers(array_filter($logger->getHandlers(), function ($handler) {
                return ! $handler instanceof HindsightMonologHandler;
            }));
        }

        return $monologHandler;
    }
}
