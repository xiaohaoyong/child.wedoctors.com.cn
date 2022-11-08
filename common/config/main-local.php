<?php
$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=child_healtha',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'aliyun' => [
            'class' => 'saviorlv\aliyun\Sms',
            'accessKeyId' => 'LTAId7nYmFlmKGcI',
            'accessKeySecret' => 'VmjZN9kalP4kqhaoIP20AUJDZnNMI9'
        ],
'cache'=>['class'=>'yii\caching\FileCache'],
        'rdmp'=>[
            'class' => 'common\vendor\RedisConnection',
            'hostname' => '127.0.0.1',
            'port' => '6379',
            'database' => 0,
        ],
       'redis'=>[
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 1,
        ],
/*'rd'=>[
            'class' => 'common\vendor\RedisConnection',
            'hostname' => 'r-m5eplvkkblhixciozhpd.redis.rds.aliyuncs.com',
            'port' => 6379,
            'password' => '307476645z!!',
            'database' => 1,
        ],
'rd0'=>[
            'class' => 'common\vendor\RedisConnection',
            'hostname' => 'r-m5eplvkkblhixciozhpd.redis.rds.aliyuncs.com',
            'port' => 6379,
            'password' => '307476645z!!',
            'database' => 0,
        ],*/

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['127.0.0.1', '::1','*'],
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
