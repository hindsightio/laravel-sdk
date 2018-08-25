<?php namespace Hindsight\Tests\Formatting\Formatters;

use DateTime;
use DateTimeZone;
use Hindsight\Formatting\Formatters\DatetimeToMillisecondsFormatter;
use Hindsight\Tests\BaseTest;

class DatetimeToMillisecondsFormatterTest extends BaseTest
{
    public function test_it_formats_datetime_objects_to_microsecond_strings()
    {
        $subject = new DatetimeToMillisecondsFormatter();
        $result = $subject->format([
            'datetime' => new DateTime('2000-01-01', new DateTimeZone('UTC'))
        ]);
        $this->assertEquals(['timestamp' => '946684800000'], $result);
    }

    public function test_php71_millisecond_rounding_edge_case()
    {
        // should succeed, 1ms early
        $subject = new DatetimeToMillisecondsFormatter();
        $result = $subject->format([
            'datetime' => new DateTime("2017-01-01 00:00:00.998000")
        ]);
        $this->assertEquals(['timestamp' => 1483225200998], $result);

        // should succeed, no rounding
        $result = $subject->format([
            'datetime' => new DateTime("2017-01-01 00:00:00.999000")
        ]);
        $this->assertEquals(['timestamp' => 1483225200999], $result);

        /*
         * Would normally fail in php <7.2, see https://bugs.php.net/bug.php?id=74753.
         * Hindsight's formatter should adjust for this.
         */
        $result = $subject->format([
            'datetime' => new DateTime("2017-01-01 00:00:00.999500")
        ]);
        $this->assertEquals(['timestamp' => 1483225200999], $result);
        $this->assertNotEquals(['timestamp' => 14832252001000], $result);

        // should work normally
        $result = $subject->format([
            'datetime' => new DateTime("2017-01-01 00:00:01.000000")
        ]);
        $this->assertEquals(['timestamp' => 1483225201000], $result);
    }
}