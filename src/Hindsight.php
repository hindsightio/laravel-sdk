<?php namespace Hindsight;

use Decahedron\StickyLogging\StickyContextProcessor;
use Hindsight\Formatting\HindsightEventFormatter;
use Hindsight\Remote\HindsightTransmitter;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;

class Hindsight
{
    /**
     * @var HindsightTransmitter
     */
    private $transmitter;
    /**
     * @var HindsightEventFormatter
     */
    private $formatter;

    public function __construct(HindsightTransmitter $transmitter, HindsightEventFormatter $formatter)
    {
        $this->transmitter = $transmitter;
        $this->formatter = $formatter;
    }

    public function setup(Logger $logger)
    {
        return $logger->pushHandler(
            new WhatFailureGroupHandler([
                (new BufferHandler(
                    new HindsightMonologHandler($this->transmitter, $this->formatter)
                ))->pushProcessor(new StickyContextProcessor)
            ])
        );
    }
}
