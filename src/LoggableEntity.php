<?php namespace Hindsight;

interface LoggableEntity
{
    /**
     * Return an array of data to be logged.
     *
     * @return array
     */
    public function toLoggableArray(): array;
}
