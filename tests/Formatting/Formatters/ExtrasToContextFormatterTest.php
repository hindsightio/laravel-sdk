<?php

namespace Hindsight\Tests\Formatting\Formatters;

use Hindsight\Formatting\Formatters\ExtrasToContextFormatter;
use Hindsight\Tests\BaseTest;

class ExtrasToContextFormatterTest extends BaseTest
{
    public function test_it_ignores_empty_contexts_or_extras()
    {
        $subject = new ExtrasToContextFormatter();
        $result = $subject->format([
            'context' => [],
        ]);
        $this->assertEquals([
            'context' => []
        ], $result);

        $result = $subject->format([
            'context' => [
                'context1' => 'val1',
                'context2' => 'val2',
            ],
        ]);
        $this->assertEquals([
            'context' => [
                'context1' => 'val1',
                'context2' => 'val2',
            ]
        ], $result);

        $result = $subject->format([
            'extra' => [
                'extra1' => 'val3',
                'extra2' => 'val4',
            ],
        ]);
        $this->assertEquals([
            'context' => [
                '_extra_extra1' => 'val3',
                '_extra_extra2' => 'val4',
            ]
        ], $result);
    }

    public function test_it_moves_extras_to_context_with_prefix()
    {
        $subject = new ExtrasToContextFormatter();
        $result = $subject->format([
            'context' => [
                'context1' => 'val1',
                'context2' => 'val2',
            ],
            'extra' => [
                'extra1' => 'val3',
                'extra2' => 'val4',
            ],
        ]);
        $this->assertEquals([
            'context' => [
                'context1' => 'val1',
                'context2' => 'val2',
                '_extra_extra1' => 'val3',
                '_extra_extra2' => 'val4',
            ]
        ], $result);
    }
}
