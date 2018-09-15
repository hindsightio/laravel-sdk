<?php namespace Hindsight;

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
        $this->app->singleton('hindsight', Hindsight::class);
        $this->app->singleton('hindsight.transmitter', HindsightTransmitter::class);
        $this->app->singleton('hindsight.formatter', HindsightEventFormatter::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hindsight.php' => config_path('hindsight.php'),
        ], 'hindsight');

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
        } else {
            $hs->setup($log->getMonolog());
        }
    }
}
