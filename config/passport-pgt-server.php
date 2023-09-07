<?php

use Luchavez\PassportPgtServer\Http\Controllers\DefaultAuthController;

return [
    'access_token_expires_in' => env('PASSPORT_ACCESS_TOKEN_EXPIRES_IN', '15 days'),
    'refresh_token_expires_in' => env('PASSPORT_REFRESH_TOKEN_EXPIRES_IN', '30 days'),
    'personal_access_token_expires_in' => env('PASSPORT_PERSONAL_ACCESS_TOKEN_EXPIRES_IN', '6 days'),
    'hash_client_secrets' => (bool) env('PASSPORT_HASH_CLIENT_SECRETS', false),
    'routes' => [
        'register' => [
            'uri' => 'oauth/register',
            'action' => [DefaultAuthController::class, 'register'],
            'middleware' => null,
            'http_method' => 'post',
        ],
        'logout' => [
            'uri' => 'oauth/logout',
            'action' => [DefaultAuthController::class, 'logout'],
            'middleware' => 'auth:api',
            'http_method' => 'post'
        ],
        'me' => [
            'uri' => 'oauth/me',
            'action' => [DefaultAuthController::class, 'me'],
            'middleware' => 'auth:api',
            'http_method' => 'get'
        ],
    ]
];
