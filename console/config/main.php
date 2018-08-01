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
        'childexc'=>[
            'class' => 'console\controllers\ChildExcController',
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
        'beanstalk' => [
            'class' => 'udokmeci\yii2beanstalk\Beanstalk',
            'host' => '127.0.0.1', // default host
            'port' => 11300,
            'connectTimeout' => 1,
            'sleep' => false, // or int for usleep after every job
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
        ],
    ],
    'params' => $params,
];
