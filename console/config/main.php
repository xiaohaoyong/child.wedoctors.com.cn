<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],

    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'worker'=>[
            'class' => 'console\controllers\WorkerController',
        ],
        'exaupdate'=>[
            'class' => 'console\controllers\ExaUpdateController',
        ],
        'dataupdate'=>[
            'class' => 'console\controllers\DataUpdateController',
        ],
        'websocket' => [
            'class' => 'jianyan\websocket\console\WebSocketController',
            'server' => 'console\models\DloadServer',
            'host' => '0.0.0.0',// 监听地址
            'port' => 9501,// 监听端口
            'config' => [// 标准的swoole配置项都可以再此加入
                'daemonize' => false,// 守护进程执行
                'task_worker_num' => 4,//task进程的数量
                'ssl_cert_file' => '',
                'ssl_key_file' => '',
                'pid_file' => __LOG__.'downSocketServer.pid',
                'log_file' => __LOG__.'downSocketSwoole.log',
                'log_level' => 0,
            ],
        ],
        'askim' => [
            'class' => 'jianyan\websocket\console\WebSocketController',
            'server' => 'console\models\ImServer',
            'host' => '0.0.0.0',// 监听地址
            'port' => 9502,// 监听端口
            'config' => [// 标准的swoole配置项都可以再此加入
                'daemonize' => false,// 守护进程执行
                'task_worker_num' => 4,//task进程的数量
                'ssl_cert_file' => '',
                'ssl_key_file' => '',
                'pid_file' => __LOG__.'ImServer.pid',
                'log_file' => __LOG__.'ImServer.log',
                'log_level' => 0,
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
        ],
    ],
    'params' => $params,
];
