<?php namespace Hindsight\Support;

class DataMerger
{
    use EventEmitter;

    /**
     * Merges two arrays together, merging sub-arrays. This expects ALL associative arrays, except on leafs.
     *
     * @param $base
     * @param $top
     * @return array
     */
    public function merge($base, $top): array
    {
        // short-circuit if we're at a leaf node with a sequential array
        if (is_array($base) && (empty($base) || array_keys($base) === range(0, count($base) - 1))) {
            $this->fire('conclude.list-merge', $base, $top);
            return array_merge($base, $top);
        }

        $merged = $base;
        foreach ($top as $key => $value) {
            // ignore if top value is null
            if (is_null($value)) {
                $this->fire('conclude.null', isset($merged[$key]) ?? null);
                continue;
            }

            // just add if not in $top
            if(!isset($merged[$key])) {
                $this->fire('conclude.direct', $value);
                $merged[$key] = $value;
                continue;
            }

            // recursive loop if both arrays
            if (is_array($merged[$key]) && is_array($value)) {
                $this->fire('conclude.recurse');
                $merged[$key] = $this->merge($merged[$key], $value);
                continue;
            }

            // merge if array
            if (is_array($merged[$key])) {
                if (!is_array($value)) {
                    $this->fire('conclude.wrapping', $merged[$key], $value);
                    $value = [$value];
                }

                $this->fire('conclude.merging',  $merged[$key], $value);
                $merged[$key] = array_merge($merged[$key], $value);
                continue;
            }

            // new value is array, old is not, use base
            if (is_array($value)) {
                $this->fire('conclude.ignore',  $merged[$key], $value);
                continue;
            }

            $this->fire('conclude.overriding',  $merged[$key], $value);
            $merged[$key] = $value;
        }
        return $merged;
    }
}
