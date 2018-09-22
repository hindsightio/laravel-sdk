<?php

use Hindsight\Configuration\ConfigHelper as Hindsight;

/**
 * TIP: the `php artisan hindsight:verify` command will diagnose your config file for you!
 */
return [
    'enable'         => true,
    'api_key'        => env('HINDSIGHT_API_KEY'),
    'config_version' => Hindsight::CONFIG_VERSION_1,

    /**
     * Presets are ready-to-go, ready-to-run settings. All other settings in this file will override the preset; set
     * any other setting to `null` to indicate you'd like to fall back to your preset.
     *
     * In short: don't know what to do? Pick `everything` for apps in development or of low volume,
     * and `errors` to only log when errors happen, useful for apps seeing lots of activity!
     */
    'preset'         => env('APP_DEBUG', false) ? 'everything' : 'errors',

    /**
     * Customize Hindsight settings here. `null` means "fall back to preset".
     */
    'features' => [

        /**
         * Events will only be sent to Hindsight when the severity exceeds `minimum_level` (using Monolog constant
         * levels). If `fingers_crossed`, it will store and forward along past lower-level events the moment it hits
         * the minimum severity - ideal for production environments.
         */
        'filter' => [
            'minimum_level' => null,
            'fingers_crossed' => null,
        ],

        /**
         * Log every HTTP request that Laravel processes. Respects the filters above; request events are DEBUG level.
         */
        'request_logging' => [
            'enable' => null,
            'redact' => [
                'fields'  => [
                    'password', 'confirm_password', 'cvv', 'cvc', 'cvv2', 'card_number', 'ssn', 'ni_number',
                ],
                'headers' => [
                    'Authorization'
                ],
            ],
            'extras' => [
                'query_counter' => true,
            ],
        ],

        /**
         * Log native Laravel events. Respects the filters above; native laravel events are DEBUG level.
         */
        'laravel_logging' => [
            'events' => [
                \Illuminate\Database\Events\ConnectionEvent::class => null,
                \Illuminate\Database\Events\QueryExecuted::class => null,
            ],
        ],

        /**
         * Log events on Eloquent models. Respects the filters above; events are at DEBUG level. Events are formatted
         * to respect `LoggableEntity` and `Eloquent::toJson()`, in that priority. Eloquent models often contain private
         * information such as credit card information or keys, so make sure they are properly hidden!
         *
         * TIP: the `php artisan hindsight:verify` command will report on your logged models, and what fields are
         * hidden.
         */
        'eloquent_logging' => [
            'models' => [],
        ],
    ],
];
