<?php

return [
    'path' => base_path('tests/Browser'),
    'domain' => env('DUSK_DOMAIN', 'http://localhost'),
    'port' => env('DUSK_PORT', 8000),
    'baseUrl' => env('APP_URL', 'http://localhost'),
    'selenium' => [
        'url' => env('DUSK_SELENIUM_URL'),
    ],
];