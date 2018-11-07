<?php
$config= [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'aliyun' => [
            'class' => 'saviorlv\aliyun\Sms',
            'accessKeyId' => 'LTAId7nYmFlmKGcI',
            'accessKeySecret' => 'VmjZN9kalP4kqhaoIP20AUJDZnNMI9'
        ],
        'redis'=>[
            'class' => 'yii\redis\Connection',
            'hostname' => '139.129.246.51',
            'port' => 6379,
            'password' => '06ef54b23a0af',
            'database' => 1,
        ],
        'formatter'=>[
            'dateFormat'=>'php:Y-m-d',
        ],
        'beanstalk' => [
            'class' => 'udokmeci\yii2beanstalk\Beanstalk',
            'host' => '127.0.0.1', // default host
            'port' => 11300,
            'connectTimeout' => 1,
            'sleep' => false, // or int for usleep after every job
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