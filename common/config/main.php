<?php
$config= [
    'aliases' => [
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],

        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:Y-m-d',  // 更推荐使用PHP格式（兼容性更好）
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'timeFormat' => 'php:H:i:s',
        ],
        'beanstalk' => [
            'class' => 'udokmeci\yii2beanstalk\Beanstalk',
            'host' => 'localhost', // default host
            'port' => 11345,
            'connectTimeout' => 1,
            'sleep' => false, // or int for usleep after every job
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '139.129.246.51:9200'],
            ],
        ],
    ],

    'modules' => [
        'redactor' => [
            'class' => 'common\components\redactor\RedactorModule',
            'uploadDir' => dirname(dirname(__FILE__))."/../../static.i.wedoctors.com.cn/redactor/",
            'uploadUrl' => 'http://static.i.wedoctors.com.cn/redactor',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
        'redactor_reset' => [
            'class' => 'common\components\redactor\RedactorModule',
        ],
    ],
];
$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['127.0.0.1', '::1'],
    'generators' => [
        'crud' => [ //生成器名称
            'class' => 'yii\gii\generators\crud\Generator',
            'templates' => [ //设置我们自己的模板
                //模板名 => 模板路径
                'myCrud' => '@common/giitemplate/crud/default',
            ]
        ]
    ],
];
return $config;