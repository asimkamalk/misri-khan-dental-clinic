<?php
// config/security.php
return [
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | This file is for storing security-related settings.
    |
    */

    // Password requirements
    'password_min_length' => 8,
    'password_requires_mixed_case' => true,
    'password_requires_number' => true,
    'password_requires_symbol' => true,

    // Session security
    'session_lifetime' => 120, // in minutes
    'session_expire_on_close' => true,

    // CSRF protection settings
    'csrf_lifetime' => 120, // in minutes

    // Rate limiting settings
    'max_attempts' => 5, // number of attempts
    'decay_minutes' => 1, // lock time after max attempts

    // Content Security Policy settings
    'csp_enabled' => true,
    'csp_directives' => [
        'default-src' => ["'self'"],
        'style-src' => ["'self'", "'unsafe-inline'", 'fonts.googleapis.com', 'cdnjs.cloudflare.com'],
        'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'cdnjs.cloudflare.com'],
        'font-src' => ["'self'", 'fonts.gstatic.com', 'cdnjs.cloudflare.com'],
        'img-src' => ["'self'", 'data:', 'blob:'],
        'frame-src' => ["'self'"],
    ],
];