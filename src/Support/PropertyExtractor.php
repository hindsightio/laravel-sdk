<?php

namespace Hindsight\Support;

use Hindsight\LoggableEntity;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionProperty;

class PropertyExtractor
{
    /**
     * Extract the properties for the given object in array form.
     *
     * Taken with modification from Laravel Telescope.
     * @see https://github.com/laravel/telescope/blob/9dfb1b08d3cf30adcfd44c79b5aaaa1f2fe34379/src/ExtractProperties.php
     *
     * @param $from
     * @return array
     * @throws \ReflectionException
     */
    public static function extract($from)
    {
        return collect((new ReflectionClass($from))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->mapWithKeys(function ($property) use ($from) {
                $property->setAccessible(true);

                if (($value = $property->getValue($from)) instanceof LoggableEntity) {
                    return [$property->getName() => $value->toLoggableArray()];
                } elseif (($value = $property->getValue($from)) instanceof Model) {
                    return [$property->getName() => $value->toArray()];
                } elseif (is_object($value)) {
                    return [
                        $property->getName() => [
                            'class' => get_class($value),
                            'properties' => json_decode(json_encode($value), true),
                        ],
                    ];
                } else {
                    return [$property->getName() => json_decode(json_encode($value), JSON_OBJECT_AS_ARRAY)];
                }
            })->toArray();
    }
}
