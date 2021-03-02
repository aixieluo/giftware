<?php

return [
    'autoload' => false,
    'hooks' => [
        'app_init' => [
            'epay',
        ],
        'config_init' => [
            'qcloudsms',
        ],
        'sms_send' => [
            'qcloudsms',
        ],
        'sms_notice' => [
            'qcloudsms',
        ],
        'sms_check' => [
            'qcloudsms',
        ],
        'user_sidenav_after' => [
            'recharge',
        ],
    ],
    'route' => [],
    'priority' => [],
];
