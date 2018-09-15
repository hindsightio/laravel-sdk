<?php namespace Hindsight\Formatting\Formatters;

use Hindsight\LoggableEntity;

/**
 * This does NOT need to be registered manually; the HindsightEventFormatter always runs this by default at the end.
 */
class ContextNormalizingFormatter extends HindsightFormatter
{

    function format(array $record): array
    {
        $record['context'] = $this->normalize($record['context']);
        return $record;
    }

    protected function normalize($data, int $depth = 0)
    {
        if (is_array($data) || $data instanceof \Traversable) {
            $normalized = [];
            $count = 1;
            foreach ($data as $key => $value) {
                if ($count++ >= 1000) {
                    $normalized['...'] = 'Over 1000 items, aborting normalization';
                    break;
                }
                $normalized[$key] = $this->normalize($value, $depth + 1);
            }
            return $normalized;
        }
        if ($data instanceof \Throwable) {
            return $this->normalizeException($data, $depth);
        }
        if ($data instanceof LoggableEntity) {
            return $this->normalize($data->toLoggableArray());
        }
        return $data;
    }

    protected function normalizeException(\Throwable $e, int $depth = 0)
    {
        $data = [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile().':'.$e->getLine(),
        ];
        $trace = $e->getTrace();
        foreach ($trace as $frame) {
            if (isset($frame['file'])) {
                $data['trace'][] = $frame['file'].':'.$frame['line'];
            } else if (isset($frame['function']) && $frame['function'] === '{closure}') {
                $data['trace'][] = $frame['function'];
            } else {
                // We should again normalize the frames, because it might contain invalid items
                $data['trace'][] = $this->normalize($frame);
            }
        }
        if ($previous = $e->getPrevious()) {
            $data['previous'] = $this->normalizeException($previous, $depth + 1);
        }
        return $data;
    }


}
