<?php

namespace Hindsight\Formatting;

use Hindsight\Formatting\Formatters\ContextNormalizingFormatter;
use Hindsight\Formatting\Formatters\HindsightFormatter;

class HindsightEventFormatter
{
    /**
     * @var HindsightFormatter[];
     */
    protected $formatters = [];

    /**
     * Format an event from whatever rich data it may have into a format
     * Hindsight can process.
     *
     * @param array $event
     * @return array
     */
    public function format(array $event)
    {
        // loop through registered formatters
        foreach($this->formatters as $formatter) {
            $event = $formatter->format($event);
        }

        // finally, ensure everything is normalized
        $event = (new ContextNormalizingFormatter())->format($event);

        return $event;
    }

    /**
     * @param HindsightFormatter $formatter
     */
    public function pushFormatter(HindsightFormatter $formatter)
    {
        $this->formatters[] = $formatter;
    }
}
