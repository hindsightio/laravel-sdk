<?php namespace Hindsight\Formatting\Formatters;

abstract class HindsightFormatter
{
    abstract function format(array $record): array;
}