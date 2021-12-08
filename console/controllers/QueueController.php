<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/12
 * Time: 下午4:43
*/

namespace console\controllers;

use Workerman\Worker;


class QueueController extends \yii\console\Controller
{
    public function actionPing($doctorid){


        // Create a Websocket server
        $ws_worker = new Worker('websocket://0.0.0.0:8080');

// Emitted when new connection come
        $ws_worker->onConnect = function ($connection) {
            echo "New connection\n";
            $connection->send('Hello ');

        };

// Emitted when data received
        $ws_worker->onMessage = function ($connection, $data) {
            // Send hello $data
            echo $data;
            $connection->send('Hello ' . time());
        };

// Emitted when connection closed
        $ws_worker->onClose = function ($connection) {
            echo "Connection closed\n";
        };

// Run worker
        Worker::runAll();
//
//        $redis = \Yii::$app->rd;
//        while (true) {
//            $text = $redis->rpop('Queue-ping-' . $doctorid);
//            if(!$text)
//            {
//                sleep(5);
//            }
//        }5
    }
}