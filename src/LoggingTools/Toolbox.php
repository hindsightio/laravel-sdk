<?php

namespace Hindsight\LoggingTools;

use Hindsight\LoggingTools\EloquentLogging\EloquentLogger;
use Hindsight\LoggingTools\Filtering\MessageFilterer;
use Hindsight\LoggingTools\LaravelLogging\LaravelEventLogger;
use Hindsight\LoggingTools\QueryLogging\QueryLogger;
use Hindsight\LoggingTools\QueueLogging\JobLogger;
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
            'queue_logging' => JobLogger::class,
            'query_logging' => QueryLogger::class,
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
