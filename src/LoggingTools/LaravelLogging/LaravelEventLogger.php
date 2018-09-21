<?php

namespace Hindsight\LoggingTools\LaravelLogging;

use Illuminate\Contracts\Events\Dispatcher;
use Monolog\Logger;

class LaravelEventLogger
{
    /**
     * @var Dispatcher
     */
    private $eventDispatcher;

    /**
     * LaravelEventLogger constructor.
     * @param Dispatcher $eventDispatcher
     */
    public function __construct(Dispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Logger $logger, array $config)
    {
        $this->eventDispatcher->listen(array_filter($config['events']), LogEvent::class);
    }
}
