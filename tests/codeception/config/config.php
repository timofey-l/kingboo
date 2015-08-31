<?php
/**
 * Application configuration shared by all applications and test types
 */
return [
    'components' => [
        'db' => [
//            'dsn' => 'mysql:host=localhost;dbname=yii2_advanced_tests',
            'dsn' => 'pgsql:host=localhost;dbname=kingboo_test',
            'username' => 'kingboo',
            'password' => 'kingboo',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
