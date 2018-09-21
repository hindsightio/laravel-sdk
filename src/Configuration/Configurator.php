<?php namespace Hindsight\Configuration;

use Hindsight\Hindsight;
use Hindsight\Support\DataMerger;
use Illuminate\Contracts\Config\Repository;

class Configurator
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @var DataMerger
     */
    private $dataMerger;

    /**
     * Configurator constructor.
     * @param Repository $config
     * @param DataMerger $dataMerger
     */
    public function __construct(Repository $config, DataMerger $dataMerger)
    {
        $this->config = $config;
        $this->dataMerger = $dataMerger;
    }

    public function setup(): void
    {
        $preset = $this->config->get('hindsight.preset');
        $base = require __DIR__."/../Configuration/Presets/$preset.hindsight-preset.php";
        $this->config->set('hindsight', $this->dataMerger->merge($base, $this->config->get('hindsight')));
    }
}
