<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;


use common\components\WebSocketClient;
use common\helpers\WechatSendTmp;
use common\models\DataUser;
use common\models\DataUserTask;
use common\models\Log;
use common\models\Notice;
use common\models\UserLogin;
use console\models\ChildDown;
use jianyan\websocket\server\WebSocketServer;
use udokmeci\yii2beanstalk\BeanstalkController;

class ChildExcController extends BeanstalkController
{
    public $_client;
    public function listenTubes()
    {
        return ['export'];
    }

    public $lineLog;
    /**
     *
     * 日志
     * @param $value
     */
    public function addLog($value)
    {
        $this->lineLog .= "==" . $value;
    }

    public function saveLog()
    {
        $file = "/home/wwwlogs/child.wedoctors.com.cn/worker/push" . date('Y-m-d') . ".log";
        file_put_contents($file, $this->lineLog . "\n", FILE_APPEND);
    }

    public static function server(){
        $client = new WebSocketClient('127.0.0.1',9501);
        $data = $client->connect();
        return $client;
    }

    /**
     * 采集数据
     * @param $job Job
     * @return string
     */
    public function actionExport($job='')
    {
        $sentData = $job->getData();
        var_dump($sentData);
        if ($sentData) {
            $server=self::server();

            $dataUser=DataUser::findOne(['token'=>$sentData->token]);
            $dataUserTask = DataUserTask::findOne(['datauserid' => $dataUser->id, 'state' => 0]);

            $childDow=new ChildDown();
            $childDow->_server=$server;
            $childDow->_server_fd=$dataUserTask->fd;

            parse_str(urldecode($sentData->data),$postData);
            $childDow->setData($postData,$dataUser);
            $filename=$childDow->excel();
            $server->send(json_encode(['type' => 'Schedule','id'=>$dataUserTask->id,'fd'=>$childDow->_server_fd,'line'=>'done','filename'=>$filename]));

        }
        return self::DELETE;

    }
}