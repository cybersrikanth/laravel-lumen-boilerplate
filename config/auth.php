<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],


    'guards' => [
        'api' => [
            'driver' => 'api',
            'provider' => 'users',
        ],
    ]
];
