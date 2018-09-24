<?php

namespace Hindsight\LoggingTools;

use Hindsight\LoggingTools\EloquentLogging\EloquentLogger;
use Hindsight\LoggingTools\Filtering\MessageFilterer;
use Hindsight\LoggingTools\LaravelLogging\LaravelEventLogger;
use Hindsight\LoggingTools\RequestLogging\RequestLogger;
use Monolog\Logger;

class Toolbox
{
    public function pack(Logger $logger, array $features)
    {
        $availableFeatures = [
            'filter' => MessageFilterer::class,
            'request_logging' => RequestLogger::class,
            'laravel_logging' => LaravelEventLogger::class,
            'eloquent_logging' => EloquentLogger::class,
        ];

        app(AttachEnvironmentDetails::class)();

        foreach ($features as $feature => $configuration) {
            if (! isset($availableFeatures[$feature])) {
                continue;
            }

            app($availableFeatures[$feature])($logger, $configuration);
        }
    }
}
