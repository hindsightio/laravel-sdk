<?php namespace Hindsight\Formatting;

class HindsightEventFormatter
{
    /**
     * Format an event from whatever rich data it may have into a format
     * Hindsight can process.
     *
     * @param array $event
     * @return array
     */
    public function format(array $event)
    {
        $record['context'] = array_merge(
            $this->formatExtras($record['extra'] ?? []),
            $record['context'] ?? []
        );
        unset($record['extra']);

        if (! empty($record['context']['exception'])) {
            $record['context']['exception'] = $this->normalizeException($record['context']['exception']);
        }

        $record['timestamp'] = (int) $record['datetime']->format('Uv');
        unset($record['datetime']);

        $record['level'] = $this->logLevels[$record['level']];

        return $record;
    }
}