<?php
return [
    'backend' => [
        'type' => 2,
        'description' => 'Backend access.',
    ],
    'client' => [
        'type' => 1,
        'description' => 'Registered client',
        'ruleName' => 'userRole',
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Application developers group',
        'ruleName' => 'userRole',
        'children' => [
            'client',
            'backend',
        ],
    ],
];
