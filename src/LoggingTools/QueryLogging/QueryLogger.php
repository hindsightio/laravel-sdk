<?php

namespace Hindsight\LoggingTools\QueryLogging;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\QueryExecuted;
use Monolog\Logger;

class QueryLogger
{
    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * QueryLogger constructor.
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function __invoke(Logger $logger, array $config)
    {
        if ($config['enabled'] ?? false) {
            $this->events->listen(QueryExecuted::class, LogQuery::class);
        }
    }
}
