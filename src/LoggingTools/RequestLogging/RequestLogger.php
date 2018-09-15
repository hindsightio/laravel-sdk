<?php namespace Hindsight\LoggingTools\RequestLogging;

use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Foundation\Http\Kernel;

class RequestLogger
{
    /**
     * @param Kernel|KernelContract $kernel
     * @param array                 $config
     */
    public function init(KernelContract $kernel, array $config)
    {
        $kernel->prependMiddleware(new HindsightRequestLoggingMiddleware());
    }
}
