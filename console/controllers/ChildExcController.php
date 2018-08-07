<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/21
 * Time: 下午4:48
 */

namespace console\controllers;


use common\components\WebSocketClient;
use common\models\DataUser;
use common\models\DataUserTask;
use console\models\ChildDown;
use yii\base\Controller;

class ChildExcController extends Controller
{
    public $_client;
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
        $file = __LOG__."childexc" . date('Y-m-d') . ".log";
        file_put_contents($file, $this->lineLog . "\n", FILE_APPEND);
    }

    public static function server(){
        $client = new WebSocketClient('127.0.0.1',9501);
        $data = $client->connect();
        echo "\n链接成功==:".$data;

        return $client;
    }
    public static function push($value){
        $redis=\Yii::$app->rdmp;
        return $redis->lpush('childex_list',$value);
    }

    public static function pop(){
        $redis=\Yii::$app->rdmp;
        $return= $redis->brpop('childex_list',0);
        return $return[1]?$return[1]:'';

    }
    public static function srun($value){
        echo "开始任务:".$value;
        $sentData=json_decode($value,true);
        $server=self::server();
        $dataUser=DataUser::findOne(['token'=>'n7udcm4l1fk69ipu9teuqkn1qq']);
        $dataUserTask = DataUserTask::findOne(['datauserid' => $dataUser->id, 'state' => 0]);

        $childDow=new ChildDown();
        $childDow->_server=$server;
        $childDow->_server_fd=$dataUserTask->fd;

        parse_str(urldecode($sentData['data']),$postData);
        echo "开始生成表格:\n";
        $childDow->setData($postData,$dataUser);
        $filename=$childDow->excel();
        $server->send(json_encode(['type' => 'Schedule','id'=>$dataUserTask->id,'fd'=>$childDow->_server_fd,'line'=>'done','filename'=>$filename]));
        $server->close();
    }

    /**
     * 采集数据
     * @param $job Job
     * @return string
     */
    public function actionExport()
    {
        ini_set('default_socket_timeout', -1);  //不超时
        echo "开启监听任务\n";
        while (1) {
            $value = self::pop();
            if ($value) {
                self::srun($value);
            }
        }
        exit;
    }
}