<?php

return [
    'api_key' => env('OKX_API_KEY', ''),
    'secret_key' => env('OKX_SECRET_KEY', ''),
    'passphrase' => env('OKX_PASSPHRASE', ''),
    'demo' => env('OKX_DEMO', false),
    'base_url' => env('OKX_BASE_URL', 'https://www.okx.com'),
];
