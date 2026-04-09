<?php

// config/cpanel.php - Configuration for cPanel hosting
return [

    /*
    |--------------------------------------------------------------------------
    | cPanel Specific Configuration
    |--------------------------------------------------------------------------
    |
    | Settings optimized for cPanel shared hosting environment
    |
    */

    'optimized' => env('CPANEL_OPTIMIZED', true),

    /*
    |--------------------------------------------------------------------------
    | File Permissions for cPanel
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'storage' => 0755,
        'cache' => 0755,
        'logs' => 0755,
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory Limit for cPanel
    |--------------------------------------------------------------------------
    */
    'memory_limit' => env('CPANEL_MEMORY_LIMIT', '256M'),

    /*
    |--------------------------------------------------------------------------
    | Max Execution Time
    |--------------------------------------------------------------------------
    */
    'max_execution_time' => env('CPANEL_MAX_EXECUTION_TIME', 300),

    /*
    |--------------------------------------------------------------------------
    | Upload Settings for cPanel
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'max_size' => env('CPANEL_UPLOAD_MAX_SIZE', '10M'),
        'max_files' => env('CPANEL_UPLOAD_MAX_FILES', 20),
    ],

];