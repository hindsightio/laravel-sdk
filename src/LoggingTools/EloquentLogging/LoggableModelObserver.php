<?php

namespace Hindsight\LoggingTools\EloquentLogging;

use Hindsight\LoggableEntity;
use Illuminate\Database\Eloquent\Model;
use Psr\Log\LoggerInterface;

class LoggableModelObserver
{
    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * LoggableModelObserver constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->log = $logger;
    }

    public function created(Model $model)
    {
        $this->logModelEvent('created', $model);
    }

    public function updated(Model $model)
    {
        $this->logModelEvent('updated', $model);
    }

    public function deleted(Model $model)
    {
        $this->logModelEvent('deleted', $model);
    }

    protected function logModelEvent(string $event, Model $model)
    {
        $this->log->debug(sprintf('%s %s', class_basename($model), $event), [
            'model' => $model instanceof LoggableEntity ? $model->toLoggableArray() : $model->toArray(),
        ]);
    }
}
