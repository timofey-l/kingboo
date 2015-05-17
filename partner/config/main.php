<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-partner',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'BOOBOOKING',
    'controllerNamespace' => 'partner\controllers',
    'components' => [
	    'view' => [
		    'theme' => [
			    'pathMap' => [
				    '@app/views' => '@app/adminTheme2'
			    ],
		    ],
	    ],
        'user' => [
            'identityClass' => 'partner\models\PartnerUser',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'common\components\LangUrlManager',
            'rules' => [
	            // Widgets
	            'GET widget/js/<code:[\d\w]+>' => 'widget/js',
	            'GET widget/css/<code:[\d\w]+>' => 'widget/css',

	            // REST for rooms
                ['class' => 'yii\rest\UrlRule', 'controller' => 'rooms'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'roomprices'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'roomimages'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'roomavaliability'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'hotelimages'],
            ],
        ],
        'request' => [
            'class' => 'common\components\LangRequest',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
    ],
    'params' => $params,
];
