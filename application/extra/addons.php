<?php

return [
    'autoload' => false,
    'hooks' => [
        'app_init' => [
            'epay',
        ],
        'user_sidenav_after' => [
            'recharge',
        ],
    ],
    'route' => [],
    'priority' => [],
];
