<?php

namespace Hindsight\LoggingTools\LaravelLogging;

use Illuminate\Log\LogManager;

class LogEvent
{
    /**
     * @var LogManager
     */
    private $log;

    /**
     * LogEvent constructor.
     * @param LogManager $log
     */
    public function __construct(LogManager $log)
    {
        $this->log = $log;
    }

    public function handle($event)
    {
        $this->log->debug('Event dispatched', [
            'class' => get_class($event),
            'payload' => collect((new \ReflectionClass($event))->getProperties(\ReflectionProperty::IS_PUBLIC))
                ->flatMap(function (\ReflectionProperty $property) use ($event) {
                    return [$property->getName() => $property->getValue($event)];
                }),
        ]);
    }
}
