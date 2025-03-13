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

        'formatter'=>[
            'dateFormat'=>'php:Y-m-d',
        ],
        'beanstalk' => [
            'class' => 'udokmeci\yii2beanstalk\Beanstalk',
            'host' => '47.93.25.40', // default host
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