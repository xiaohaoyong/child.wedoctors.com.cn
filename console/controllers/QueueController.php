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
    public $doctorid;
    public function actionPing($doctorid){

        $this->doctorid=$doctorid;


        // Create a Websocket server
        $ws_worker = new Worker('websocket://0.0.0.0:8080');

// Emitted when new connection come
        $ws_worker->onConnect = function ($connection) {
            echo "New connection\n";
        };

// Emitted when data received
        $ws_worker->onMessage = function ($connection, $data) {
            $redis = \Yii::$app->rd;
            // Send hello $data
            $text = $redis->rpop('Queue-ping-' . $this->doctorid);
            if($text) {
                $connection->send($text);
            }
            $connection->send("4-清四大皆空逢山开道加快速度".time());
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