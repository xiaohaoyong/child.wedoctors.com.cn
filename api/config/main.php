<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$config= [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
        'v2' => [
            'class' => 'api\modules\v2\Module',
        ],
        'v3' => [
            'class' => 'api\modules\v3\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
        ],
        'log' => [
            'targets' => [
                'error_file' => [  //error_file是自定义的，方便见名知道意思
                    'logFile' => '@api/runtime/logs/error.'.date('Ymd').'.log',//日志存放目录
                    'class' => 'yii\log\FileTarget', //指定使用4个方式其中之以的文件存储方式
                    'levels' => ['error'],//存放级别，错误级别的
                    'maxLogFiles' => 100,//最多存放的日志文件数
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