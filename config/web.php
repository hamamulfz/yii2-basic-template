<?php

// $params = require __DIR__ . '/params.php';
// $db = require __DIR__ . '/db.php';
// $db_public = require __DIR__ . '/db_public.php';
// $db_language = require __DIR__ . '/db_language.php';
Yii::setAlias('@mail', dirname(__DIR__) . '/mail');
Yii::setAlias('@mdm', dirname(__DIR__) . '/components/mdm');
error_reporting(error_reporting() ^ E_DEPRECATED);
$databases = require __DIR__ . '/web-local.php';
$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$config = [
    'id' => 'kcic-sikk-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@baseweb' => '@app/web',
    ],
    'modules' => [
        // 'admin' => [
        //     'class' => 'app\components\mdm\Module',
        //     'layout' => 'left-menu',
        // ],
        'auths' => [
            'class' => 'app\modules\auths\Module',
        ],
        'masters' => [
            'class' => 'app\modules\masters\Module',
        ],
        'dashboard' => [
            'class' => 'app\modules\dasbboard\Module',
            'defaultRoute' => 'site/index',
        ],
        'notification' => [
            'class' => 'app\modules\notification\Module',
            'defaultRoute' => 'site/index',
        ],
        'mobile' => [
            'class' => 'app\modules\mobile\Module',
            'defaultRoute' => 'site/index',
        ],
        'v1' => [
            'class' => 'app\controllers\v1\Module',
            'defaultRoute' => 'site/index',
        ],
    ],
    'name' => 'KCIC - Sistem Informasi Keselamatan dan Keamanan',
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'enableCsrfValidation'   => false,
            'cookieValidationKey' => 'SiXAdG84m1xRpNbVy5vRpxFb11KRQMTp',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser'
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            // 'class' => 'yii\rbac\DbManager',
            'class' => 'app\components\DbManager',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => [
                        "check-auth",
                        "auths/profile",
                        "v1/rssi",
                        "v1/settings",
                        "v1/ssid-mapping"
                    ],
                    'extraPatterns' => [
                        'OPTIONS search' => 'options',
                        'OPTIONS my' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];

$config = yii\helpers\ArrayHelper::merge($config, $databases);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
