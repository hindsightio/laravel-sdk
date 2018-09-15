<?php

use Hindsight\Configuration\ConfigHelper as Hindsight;

/**
 * TIP: the `php artisan hindsight:verify` command well diagnose your config file for you!
 */
return [
    'enable'         => true,
    'api_key'        => env('HINDSIGHT_API_KEY'),
    'config_version' => Hindsight::CONFIG_VERSION_1,

    /**
     * Presets are ready-to-go, ready-to-run settings. All other settings in this file will override the preset; set
     * any other setting to `null` to indicate you'd like to fall back to your preset.
     *
     * In short: don't know what to do? Pick `debug` for apps in development and `production` for apps seeing lots
     * of activity!
     */
    'preset'         => env('APP_DEBUG', false) ? 'debug' : 'production',

    /**
     * Customize Hindsight settings here.
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
        ],

        /**
         * Log native Laravel events. Respects the filters above; native laravel events are DEBUG level.
         */
        'laravel_logging' => [

        ],

        /**
         * Log events on Eloquent models.
         */
    ],
];
