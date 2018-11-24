<?php

namespace Hindsight\LoggingTools\EloquentLogging;

use Hindsight\LoggableEntity;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Monolog\Logger;

class EloquentLogger
{
    protected $models;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * EloquentLogger constructor.
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function __invoke(Logger $logger, array $config)
    {
        $this->models = $config['models'];

        if (count($this->models)) {
            $this->events->listen('eloquent.*', [$this, 'recordModelAction']);
        }
    }

    public function recordModelAction($event, $data)
    {
        /** @var Model $model */
        $model = $data[0];

        if (! $this->shouldRecord($event, $model)) {
            return;
        }

        $action = $this->action($event);

        logger()->debug(sprintf('%s %s', class_basename($model), $action), [
            'attributes' => $model instanceof LoggableEntity ? $model->toLoggableArray() : $model->toArray(),
            'changes' => $model->getChanges(),
            'code' => Str::lower('hindsight.model-logging.'.$action),
            'model' => get_class($model),
        ]);
    }

    /**
     * Determine if the Eloquent event should be recorded.
     *
     * @param  string  $eventName
     * @return bool
     */
    private function shouldRecord($eventName, Model $model)
    {
        return Str::is([
            '*created*', '*updated*', '*restored*', '*deleted*',
        ], $eventName) &&
            (in_array('*', $this->models) || in_array(get_class($model), $this->models));
    }

    /**
     * Extract the Eloquent action from the given event.
     *
     * @param  string  $event
     * @return mixed
     */
    private function action($event)
    {
        preg_match('/\.(.*):/', $event, $matches);

        return $matches[1];
    }
}
