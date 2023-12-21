<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library in superban. This connection is used when another is
    | not explicitly specified when executing a given caching function within the superban libary.
    |
    | ** All laravel cache stores are supported **
    |
    */
    'cache_driver' => env('SUPERBAN_CACHE_DRIVER', 'file'),

    /*
     * The key that will be used to ban the user and stored in the cache.
     * Supported keys are: ip, email, and id .
     */

    'identity_key' => env('SUPERBAN_IDENTITY_KEY', 'ip'),
];
