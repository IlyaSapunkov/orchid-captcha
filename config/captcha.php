<?php

declare(strict_types=1);

return [
    'length' => 5, // length of the captcha text
    'letters' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'vowels' => '0123456789',
    'expiration_time' => 60, // in seconds
    'show_in_login_form' => false,
];
