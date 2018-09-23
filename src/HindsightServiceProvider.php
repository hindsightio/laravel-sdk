<?php namespace Hindsight;

use Hindsight\Commands\VerifyConfigurationCommand;
use Hindsight\Configuration\Configurator;
use Hindsight\Formatting\Formatters\DatetimeToMillisecondsFormatter;
use Hindsight\Formatting\Formatters\ExtrasToContextFormatter;
use Hindsight\Formatting\Formatters\MonologToRFC5424SeverityFormatter;
use Hindsight\Formatting\HindsightEventFormatter;
use Hindsight\Remote\HindsightTransmitter;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class HindsightServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hindsight.php', 'hindsight');

        $this->app->singleton('hindsight', Hindsight::class);
        $this->app->bind(HindsightTransmitter::class, function ($app) {
            return new HindsightTransmitter($app['config']->get('hindsight.api_url'));
        });
        $this->app->singleton('hindsight.transmitter', HindsightTransmitter::class);
        $this->app->singleton('hindsight.formatter', HindsightEventFormatter::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                VerifyConfigurationCommand::class,
            ]);
        }
        $this->publishes([
            __DIR__.'/../config/hindsight.php' => config_path('hindsight.php'),
        ], 'hindsight');


        // Merge the config with the specified preset
        $this->app->make(Configurator::class)->setup();

        /** @var LogManager $log */
        $log = $this->app['log'];
        /** @var Hindsight $hs */
        $hs = $this->app['hindsight'];

        // assign API token
        /** @var HindsightTransmitter $hsTransmitter */
        $hsTransmitter = $this->app['hindsight.transmitter'];
        $hsTransmitter->setApiToken(config('hindsight.api_key'));

        // set default formatters
        /** @var HindsightEventFormatter $hsFormatter */
        $hsFormatter = $this->app['hindsight.formatter'];
        $hsFormatter->pushFormatter($this->app->make(DatetimeToMillisecondsFormatter::class));
        $hsFormatter->pushFormatter($this->app->make(ExtrasToContextFormatter::class));
        $hsFormatter->pushFormatter($this->app->make(MonologToRFC5424SeverityFormatter::class));

        // On Laravel 5.6, we will register the Hindsight log driver
        if ($log instanceof LogManager) {
            $log->extend('hindsight', function ($app, array $config) use ($hs) {
                return $hs->setup(new Logger(config('app.environment')));
            });
            /** @var \Illuminate\Log\Logger $driver */
            $driver = $log->driver();
            if ($driver->getLogger() instanceof Logger && collect($driver->getLogger()->getHandlers())->first(function ($handler) {
                return $handler instanceof HindsightMonologHandler;
            }) !== null) {
                $hs->setup($driver->getLogger());
            }
        } else {
            $hs->setup($log->getMonolog());
        }
    }
}
