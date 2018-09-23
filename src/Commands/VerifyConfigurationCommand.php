<?php

namespace Hindsight\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Psr\Log\LoggerInterface;

class VerifyConfigurationCommand extends Command
{
    protected $signature = 'hindsight:verify';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Repository
     */
    private $config;

    /**
     * VerifyConfigurationCommand constructor.
     * @param LoggerInterface $logger
     * @param Repository      $config
     */
    public function __construct(LoggerInterface $logger, Repository $config)
    {
        parent::__construct();
        $this->logger = $logger;
        $this->config = $config;
    }

    public function handle()
    {
        $this->info('Sending log message to Hindsight...');
        $this->logger->info('Hindsight setup working', [
            'code' => 'hindsight-verification.success',
            'configuration' => $this->config->get('hindsight'),
        ]);
        $this->info('✔ Log message transmitted to Hindsight');
    }
}
