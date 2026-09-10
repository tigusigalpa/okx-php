<?php

return [
    'api_key' => env('OKX_API_KEY', ''),
    'secret_key' => env('OKX_SECRET_KEY', ''),
    'passphrase' => env('OKX_PASSPHRASE', ''),
    'demo' => env('OKX_DEMO', false),
    // global, us (also AU), eea, or tr. Pick the value matching where the OKX account was registered.
    'region' => env('OKX_REGION', 'global'),
    // Optional REST endpoint override. Leave empty to use the endpoint for the selected region.
    'base_url' => env('OKX_BASE_URL'),
];
