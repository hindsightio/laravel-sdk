<?php

namespace Hindsight\LoggingTools\QueryLogging;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Str;

class LogQuery
{
    /**
     * Log an executed query.
     *
     * Heavily inspired by Laravel Telescope.
     * @see https://github.com/laravel/telescope/blob/9dfb1b08d3cf30adcfd44c79b5aaaa1f2fe34379/src/Watchers/QueryWatcher.php
     *
     * @param QueryExecuted $event
     */
    public function handle(QueryExecuted $event)
    {
        $caller = $this->getCallerFromStackTrace();

        logger()->debug('Query executed', [
            'code' => 'hindsight.query-logging.query-executed',
            'connection' => $event->connectionName,
            'bindings' => $event->connection->prepareBindings($event->bindings),
            'sql' => $event->sql,
            'time' => number_format($event->time, 2),
            'file' => $caller['file'],
            'line' => $caller['line'],
        ]);
    }

    /**
     * Find the first frame in the stack trace outside of Hindsight.
     *
     * Copied from Laravel Telescope, modified for Hindsight.
     * @see https://github.com/laravel/telescope/blob/9dfb1b08d3cf30adcfd44c79b5aaaa1f2fe34379/src/Watchers/QueryWatcher.php#L68-L86
     *
     * @return array
     */
    protected function getCallerFromStackTrace()
    {
        $trace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))->forget(0);

        return $trace->first(function ($frame) {
            if (! isset($frame['file'])) {
                return false;
            }

            return ! Str::contains($frame['file'],
                [
                    base_path('vendor'.DIRECTORY_SEPARATOR.'hindsight'),
                    base_path('vendor'.DIRECTORY_SEPARATOR.'laravel'),
                ]
            );
        });
    }
}
