<?php

namespace Hindsight\LoggingTools\EloquentLogging;

use Monolog\Logger;

class EloquentLogger
{
    public function __invoke(Logger $logger, array $config)
    {
        foreach ($config['models'] as $model) {
            $model::observe(LoggableModelObserver::class);
        }
    }
}
