<?php

namespace Hindsight\LoggingTools\LaravelLogging;

use Hindsight\Support\PropertyExtractor;
use Illuminate\Log\LogManager;

class LogEvent
{
    /**
     * @var LogManager
     */
    private $log;

    public function handle($event)
    {
        logger()->debug('Event dispatched', [
            'class' => get_class($event),
            'payload' => PropertyExtractor::extract($event),
            'code' => 'hindsight.event-logging.dispatched',
        ]);
    }
}
