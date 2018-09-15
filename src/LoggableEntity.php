<?php namespace Hindsight;

interface LoggableEntity
{
    public function toLoggableArray(): array;
}
