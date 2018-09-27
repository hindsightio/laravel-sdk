<?php

namespace Hindsight;

use Hindsight\Formatting\HindsightEventFormatter;
use Hindsight\Remote\HindsightTransmitter;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;

class HindsightMonologHandler extends AbstractHandler
{
    /**
     * @var HindsightTransmitter
     */
    private $transmitter;
    /**
     * @var HindsightEventFormatter
     */
    private $eventFormatter;

    public function __construct(
        HindsightTransmitter $transmitter,
        HindsightEventFormatter $eventFormatter
    ) {
        $this->transmitter = $transmitter;
        $this->eventFormatter = $eventFormatter;
        parent::__construct();
    }

    public function handle(array $record)
    {
        $this->submitRecordsToHindsight($record);
    }

    public function handleBatch(array $records)
    {
        $this->submitRecordsToHindsight($records);
    }

    protected function submitRecordsToHindsight(array $records)
    {
        $records = array_map(function ($record) {
            return $this->eventFormatter->format($this->processRecord($record));
        }, $records);
        $this->transmitter->sendForIngest($records);
    }

    protected function processRecord(array $record)
    {
        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = call_user_func($processor, $record);
            }
        }
        return $record;
    }
}
