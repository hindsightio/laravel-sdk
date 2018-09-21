<?php

namespace Hindsight\LoggingTools\Filtering;

use Hindsight\Support\MonologReader;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Logger;

class MessageFilterer
{
    public function __invoke(Logger $logger, array $config)
    {
        $hindsight = MonologReader::retrieveHindsightHandler($logger);
        if (! $hindsight) {
            return;
        }

        if ($config['fingers_crossed']) {
            $hindsight->setLevel(Logger::DEBUG);
            $logger->pushHandler(new FingersCrossedHandler($hindsight, new ErrorLevelActivationStrategy($config['minimum_level'])));
        } else {
            $hindsight->setLevel($config['minimum_level']);
        }
    }
}
