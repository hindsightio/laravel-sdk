<?php namespace Hindsight\Formatting\Formatters;

use Hindsight\Configuration\Configurator;

class RedactingFormatter extends HindsightFormatter
{
    /**
     * @var array
     */
    private $redactedFields;
    /**
     * @var array
     */
    private $redactedHeaders;

    public function __construct(array $redactedFields, array $redactedHeaders)
    {
        $this->redactedFields = $redactedFields;
        $this->redactedHeaders = $redactedHeaders;
    }

    public function format(array $record): array
    {
        return $record;
    }
}
