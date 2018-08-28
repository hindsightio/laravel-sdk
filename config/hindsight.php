<?php

use Hindsight\Configuration\ConfigHelper as Hindsight;

return [
    'attach_request_id_to_response' => true,
    'api_key'                       => '',

    'api_url'        => Hindsight::API_URL,
    'config_version' => Hindsight::CONFIG_VERSION_1,

    'preset' =>  env('APP_DEBUG', false) ? Hindsight::DEBUG_PRESET : Hindsight::REPORTER_PRESET,

    'features' => [
        'request_logging' => [
            'enable' => true,
            'redact' => [
                'fields'  => [
                    'password',
                    'confirm_password',
                    'cvv',
                    'cvc',
                    'cvv2',
                    'card_number',
                    'ssn',
                    'ni_number',
                ],
                'headers' => [
                    'Authorization'
                ],
            ],
        ],
    ],
];
