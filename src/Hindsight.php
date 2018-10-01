<?php

namespace Hindsight;

use Decahedron\StickyLogging\StickyContextProcessor;
use Hindsight\Formatting\HindsightEventFormatter;
use Hindsight\LoggingTools\Toolbox;
use Hindsight\Remote\HindsightTransmitter;
use Illuminate\Contracts\Config\Repository;
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

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Toolbox
     */
    private $toolbox;

    /**
     * Hindsight constructor.
     * @param HindsightTransmitter    $transmitter
     * @param HindsightEventFormatter $formatter
     * @param Repository              $config
     * @param Toolbox                 $toolbox
     */
    public function __construct(
        HindsightTransmitter $transmitter,
        HindsightEventFormatter $formatter,
        Repository $config,
        Toolbox $toolbox
    )
    {
        $this->transmitter = $transmitter;
        $this->formatter   = $formatter;
        $this->config      = $config;
        $this->toolbox     = $toolbox;
    }

    public function setup(Logger $logger)
    {
        $this->toolbox->pack($logger, $this->config->get('hindsight.features'));

        return $logger->pushHandler(
            new WhatFailureGroupHandler([
                (new BufferHandler(
                    new HindsightMonologHandler($this->transmitter, $this->formatter)
                ))->pushProcessor(new StickyContextProcessor)
            ])
        );
    }
}
