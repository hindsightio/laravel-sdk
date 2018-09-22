<?php

use Hindsight\Configuration\ConfigHelper as Hindsight;

return [
    'features' => [
        'filter' => [
            'minimum_level' => \Monolog\Logger::NOTICE,
            'fingers_crossed' => true,
        ],
        'request_logging' => [
            'enable' => true,
            'redact' => [
                'fields'  => [],
                'headers' => [],
            ],
        ],
        'laravel_logging' => [
            'events' => [
                \Illuminate\Database\Events\ConnectionEvent::class => null,
                \Illuminate\Database\Events\QueryExecuted::class => null,
            ],
        ],
        'eloquent_logging' => [
            'models' => [],
        ],
    ],
];
