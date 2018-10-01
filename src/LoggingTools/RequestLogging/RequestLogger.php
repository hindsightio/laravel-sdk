<?php

namespace Hindsight\LoggingTools\RequestLogging;

use Illuminate\Contracts\Http\Kernel as KernelContract;
use Monolog\Logger;

class RequestLogger
{
    /**
     * @var KernelContract
     */
    protected $kernel;

    /**
     * RequestLogger constructor.
     * @param KernelContract $kernel
     */
    public function __construct(KernelContract $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param Logger $logger
     * @param array  $config
     */
    public function __invoke(Logger $logger, array $config)
    {
        $this->kernel->prependMiddleware(new HindsightRequestLoggingMiddleware());
    }
}
