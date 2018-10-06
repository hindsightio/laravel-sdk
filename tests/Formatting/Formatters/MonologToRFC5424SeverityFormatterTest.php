<?php

namespace Hindsight\Tests\Formatting\Formatters;

use Hindsight\Formatting\Formatters\MonologToRFC5424SeverityFormatter;
use Hindsight\Tests\BaseTest;
use Monolog\Logger;

class MonologToRFC5424SeverityFormatterTest extends BaseTest
{
    public function test_it_converts_severity_formats()
    {
        $subject = new MonologToRFC5424SeverityFormatter();
        $result = $subject->format([
            'level' => Logger::ERROR
        ]);
        $this->assertEquals(['level' => LOG_ERR], $result);
    }
}
