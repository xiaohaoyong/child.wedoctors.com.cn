<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$config= [
    'id' => 'app-ask',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'ask\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'ask\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-ask',
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-ask', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-ask',
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
            'enablePrettyUrl' => true,  //美化url==ture
            'enableStrictParsing' => false,  //不启用严格解析
            'showScriptName' => false,   //隐藏index.php
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
            ],
        ]
    ],
    'params' => $params,
];
return $config;