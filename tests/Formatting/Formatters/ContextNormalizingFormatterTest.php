<?php namespace Hindsight\Tests\Formatting\Formatters;

use Hindsight\Formatting\Formatters\ContextNormalizingFormatter;
use Hindsight\Tests\BaseTest;

class ContextNormalizingFormatterTest extends BaseTest
{
    public function test_it_normalizes_exceptions()
    {
        $subject = new ContextNormalizingFormatter();
    }
}