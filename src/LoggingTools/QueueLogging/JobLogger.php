<?php

namespace Hindsight\LoggingTools\QueueLogging;

use Hindsight\Support\PropertyExtractor;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Monolog\Logger;

class JobLogger
{
    protected $config;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * JobLogger constructor.
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function __invoke(Logger $logger, array $config)
    {
        $this->config = $config;

        if (count($this->config['jobs'])) {
            $this->events->listen(JobProcessed::class, [$this, 'recordProcessedJob']);
            $this->events->listen(JobFailed::class, [$this, 'recordFailedJob']);
        }
    }

    public function recordProcessedJob(JobProcessed $event)
    {
        if (! (in_array('*', $this->config['jobs']) || in_array($event->job->getName(), $this->config['jobs']))) {
            return;
        }

        logger()->debug('Job processed', array_merge([
                'status' => 'processed',
                'code' => 'hindsight.queue-logging.job-processed',
                'connection' => $event->connectionName,
            ], $this->defaultJobData($event->job))
        );
    }

    /**
     * Record a job failed processing.
     *
     * @param JobFailed $event
     */
    public function recordFailedJob(JobFailed $event)
    {
        logger()->debug('Job processed', array_merge([
                'status' => 'failed',
                'code' => 'hindsight.queue-logging.job-failed',
                'connection' => $event->connectionName,
                'exception' => $event->exception,
            ], $this->defaultJobData($event->job))
        );
    }

    public function defaultJobData(Job $job)
    {
        $payload = $job->payload();

        return [
            'queue' => $job->getQueue(),
            'tries' => $job->maxTries(),
            'name' => $job->getName(),
            'timeout' => $job->timeout(),
            'data' => isset($payload['data']['command']) ?
                PropertyExtractor::extract($payload['data']['command']) :
                $payload['data'],
        ];
    }
}
