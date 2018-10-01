<?php

namespace Hindsight\Tests\Formatting\Formatters;

use Hindsight\Formatting\Formatters\ContextNormalizingFormatter;
use Hindsight\LoggableEntity;
use Hindsight\Tests\BaseTest;

class ContextNormalizingFormatterTest extends BaseTest
{
    public function test_it_normalizes_scalar_data()
    {
        $subject = new ContextNormalizingFormatter();

        $result = $subject->format([
            'context' => ['hello' => 1, 'world' => 10]
        ]);
        $this->assertEquals(['context' => ['hello' => 1, 'world' => 10]], $result);
    }

    public function test_it_normalizes_exceptions()
    {
        $subject = new ContextNormalizingFormatter();

        $result = $subject->format([
            'context' => ['hello' => 1, 'e' => new \Exception('exception message',  200)]
        ]);
        $this->assertArraySubset(['context' => [
            'hello' => 1,
            'e' => [
                'class' => 'Exception',
                'message' => 'exception message',
                'code' => 200,
            ]
        ]], $result);
    }

    public function test_it_normalizes_loggables()
    {
         $testLoggable = new class(20) implements LoggableEntity {
            public $val;
            public function __construct($val)
            {
                $this->val = $val;
            }
             public function toLoggableArray(): array
            {
                return ['hello' => 1, 'value' => $this->val];
            }
        };

        $subject = new ContextNormalizingFormatter();

        $result = $subject->format([
            'context' => ['hello' => 1, 'c' => $testLoggable]
        ]);
        $this->assertEquals(['context' => ['hello' => 1, 'c' => [
            'hello' => 1,
            'value' => 20,
        ]]], $result);
    }
}
