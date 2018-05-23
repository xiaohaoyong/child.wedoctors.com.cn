<?php
$config= [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter'=>[
            'dateFormat'=>'php:Y-m-d',
        ],
    ],
    'modules' => [
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => dirname(dirname(__FILE__))."/../../static.i.wedoctors.com.cn/redactor/",
            'uploadUrl' => 'http://static.i.wedoctors.com.cn/redactor',
            'imageAllowExtensions'=>['jpg','png','gif']
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