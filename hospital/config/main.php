<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-hospital',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'hospital\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',//yii2-admin的导航菜单
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-hospital',
        ],
        'user' => [
            'identityClass' => 'hospital\models\UserLogin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-hospital', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the databackend
            'name' => 'advanced-hospital',
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
        'assetManager' => [
            'appendTimestamp' => true,//实测对性能有影响
            'linkAssets' => true, // 刷新后就可以清除缓存
            'forceCopy'=>true,
        ],
    ],'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*'

        ]
    ],
    'language'=>'zh-CN',
    'params' => $params,
];
