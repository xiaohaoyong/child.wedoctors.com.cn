<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=zzy_local',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'aliyun' => [
            'class' => 'saviorlv\aliyun\Sms',
            'accessKeyId' => 'LTAId7nYmFlmKGcI',
            'accessKeySecret' => 'VmjZN9kalP4kqhaoIP20AUJDZnNMI9'
        ],
/*        'rdmp'=>[
            'class' => 'common\vendor\RedisConnection',
            'hostname' => '139.129.246.51',
            'port' => '6379',
            'password' => '06ef54b23a0af',
            'database' => 0,
        ],*/
/*        'redis'=>[
            'class' => 'yii\redis\Connection',
            'hostname' => '139.129.246.51',
            'port' => 6379,
            'password' => '06ef54b23a0af',
            'database' => 1,
        ],*/
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

