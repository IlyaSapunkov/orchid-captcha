<?php

declare(strict_types=1);

return [
    'length' => 5, // length of the captcha text
    'letters' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'vowels' => '0123456789',
    'expiration_time' => 60, // in seconds
    'show_in_login_form' => false,
    'use_noise' => true,
    'sizes' => [
        'width' => 120,
        'height' => 50,
        'font' => [
            'text' => 30,
            'icon' => 10,
        ],
    ],
    'colors' => [
        'background' => 0xFFFFFF,
        'text' => 0x2040A0,
        'icon' => 0x808080,
    ],
];
