<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language'=>'ru-RU',
    'name' => 'king-boo.com',
    'components' => [
        'assetManager' => [
            'linkAssets' => true,
        ],
        'cache' => [
			'class' => 'yii\caching\FileCache',
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
