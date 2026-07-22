<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Discogs API
    |--------------------------------------------------------------------------
    |
    | Instellingen voor de Discogs API.
    |
    */

    'base_url' => env('DISCOGS_BASE_URL', 'https://api.discogs.com'),

    'token' => env('DISCOGS_TOKEN'),

    'user_agent' => env(
        'DISCOGS_USER_AGENT',
        'CdSingleArbitrage/1.0 (+https://github.com/jouw-gebruikersnaam/cd-single-webshop)'
    ),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */

    'cache_enabled' => env('DISCOGS_CACHE', true),

    'cache_ttl' => env('DISCOGS_CACHE_TTL', 60 * 60 * 24 * 30), // 30 dagen

    /*
    |--------------------------------------------------------------------------
    | Rate limiting
    |--------------------------------------------------------------------------
    */

    'timeout' => env('DISCOGS_TIMEOUT', 10),

];