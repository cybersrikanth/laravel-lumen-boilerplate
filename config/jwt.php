<?php

return [
    'secret' => env('JWT_SECRET'),
    'iss' => env('APP_URL', 'http://localhost'),
    'expires_after_in_seconds' => env('JWT_EXPIRES_IN', 3600)
];
