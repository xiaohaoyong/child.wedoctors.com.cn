<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-databackend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'databackend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-databackend',
        ],
        'user' => [
            'identityClass' => 'databackend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-databackend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the databackend
            'name' => 'advanced-databackend',
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
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'language'=>'zh-CN',
    'params' => $params,
];
