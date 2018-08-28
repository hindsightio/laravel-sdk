<?php namespace Hindsight\Configuration;

use Hindsight\Hindsight;

class Configurator
{
    public function parse(array $config): Hindsight
    {
        $nexus = new Hindsight();

        // assign presets
        if (array_get($config, 'preset')) {

        }
    }
}
