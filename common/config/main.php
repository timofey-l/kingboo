<?php
use common\components\PrimaApi;

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language'=>'ru-RU',
    'name' => 'king-boo.com',
    'bootstrap' => ['log'],
    'components' => [
        'primaApi' => [
            'class' => PrimaApi::className(),
            'apiLogin' => 'king-boo',
            'apiPassword' => 'uD6wI7S2Hxu',
            'apiKey' => 'edf3e898d53bc3aa5a1d7664c629ddc11168de13',
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
        'cache' => [
			'class' => 'yii\caching\FileCache',
		],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'message' => [
                        'from' => ['no-reply@king-boo.com'],
                        'to' => ['admin@king-boo.com' => 'Admin', 'timofeylyzhenkov@gmail.com' => 'Timofey Lyzhenkov'],
                        'subject' => 'Errors at king-boo.com',
                    ],
                ],
            ],
        ],
//        'authManager' => [
//            'class' => 'yii\rbac\PhpManager',
//            'defaultRoles' => ['client', 'admin'],
//            'itemFile' => '@common/components/rbac/items.php',
//            'assignmentFile' => '@common/components/rbac/assignments.php',
//            'ruleFile' => '@common/components/rbac/rules.php'
//        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],

    ],
];
