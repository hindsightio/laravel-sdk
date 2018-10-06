<?php

namespace Hindsight\Formatting\Formatters;

class ExtrasToContextFormatter extends HindsightFormatter
{
    function format(array $record): array
    {
        $record['context'] = array_merge(
            $this->formatExtras($record['extra'] ?? []),
            $record['context'] ?? []
        );

        unset($record['extra']);

        return $record;
    }

    protected function formatExtras(array $extra)
    {
        $prefixer = function ($value, $key) {
            return ['_extra_' . $key => $value];
        };

        $formatted = array_map($prefixer, $extra, array_keys($extra));

        return count($formatted) ? array_merge(...$formatted) : [];
    }
}
