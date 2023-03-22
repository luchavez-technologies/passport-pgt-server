<?php

return [
    'access_token_expires_in' => [
        'time_unit' => env('PPS_AT_EXPIRE_UNIT', 'days'),
        'time_value' => env('PPS_AT_EXPIRE_VALUE', 15),
    ],
    'refresh_token_expires_in' => [
        'time_unit' => env('PPS_RT_EXPIRE_UNIT', 'days'),
        'time_value' => env('PPS_RT_EXPIRE_VALUE', 30),
    ],
    'personal_access_token_expires_in' => [
        'time_unit' => env('PPS_PAT_EXPIRE_UNIT', 'days'),
        'time_value' => env('PPS_PAT_EXPIRE_VALUE', 6),
    ],
    'hash_client_secrets' => (bool) env('PPS_HASH_CLIENT_SECRETS', false),
];
