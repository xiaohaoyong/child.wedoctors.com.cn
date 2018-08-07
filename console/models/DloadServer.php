<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/7/27
 * Time: 下午3:03
 */

namespace console\models;


use common\models\DataUser;
use common\models\DataUserTask;
use console\controllers\ChildExcController;
use http\Url;
use jianyan\websocket\server\WebSocketServer;
use yii\helpers\Html;

class DloadServer extends WebSocketServer
{
    public function __construct($host, $port, $mode, $socketType, $type, $config)
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_mode = $mode;
        $this->_socketType = $socketType;
        $this->_type = $type;
        $this->_config = $config;
    }

    /**
     * 启动进程
     */
    public function run()
    {
        if ($this->_type == 'ws') {
            $this->_server = new \swoole_websocket_server($this->_host, $this->_port, $this->_mode, $this->_socketType);
        } else {
            $this->_server = new \swoole_websocket_server($this->_host, $this->_port, $this->_mode, $this->_socketType | SWOOLE_SSL);
        }

        $this->_server->set($this->_config);
        $this->_server->on('open', [$this, 'onOpen']);
        $this->_server->on('message', [$this, 'onMessage']);

        $this->_server->on('task', [$this, 'onTask']);
        $this->_server->on('finish', [$this, 'onFinish']);
        $this->_server->on('close', [$this, 'onClose']);
        $this->_server->start();
    }

    public function onMessage($server, $frame)
    {
        $data = json_decode($frame->data, true);
        if ($data['type'] == 'Apply') {
            $dataUser = DataUser::findOne(['token' => $data['token']]);

            if ($dataUser) {
                $dataUserTask = DataUserTask::findOne(['datauserid' => $dataUser->id, 'state' => 0]);
                if ($dataUserTask) {
                    echo "更新任务";
                    $dataUserTask->fd = $frame->fd;
                    $dataUserTask->save();
                    $server->push($frame->fd, json_encode(['type' => 'Apply', 'Result' => '成功', 'state' => 3]));
                } else {
                    echo "创建任务";
                    //创建任务
                    $dataUserTask = new DataUserTask();
                    $dataUserTask->createtime = time();
                    $dataUserTask->datauserid = $dataUser->id;
                    $dataUserTask->note = str_replace('ChildInfoSearchModel', '', $data['data']);
                    $dataUserTask->fd = $frame->fd;
                    $dataUserTask->save();
                    $data['task_id'] = $dataUserTask->id;

                    ChildExcController::push(json_encode($data));
                    echo "结束";
                }
            } else {
                $server->push($frame->fd, json_encode(['type' => 'Apply', 'Result' => '失败', 'state' => 0]));
            }
        }elseif($data['type']=='Schedule'){

            if($data['line']!='done'){

                if($server->exist($data['fd'])) {
                    $server->push($data['fd'], json_encode(['type' => 'Apply', 'Result' => '成功', 'state' => 1, 'line' => $data['line']]));
                }
            }else{
                $url=Html::a('点击下载','http://static.wedoctors.com.cn/'.$data['filename'],['target'=>"_blank"]);

                var_dump($data['id']);
                $dataUserTask = DataUserTask::findOne($data['id']);

                if($dataUserTask) {
                    $dataUserTask->state = 1;
                    $dataUserTask->result = $data['filename'];
                    $dataUserTask->save();
                    var_dump($dataUserTask->firstErrors);
                }

                $server->push($data['fd'], json_encode(['type' => 'Apply', 'Result' => '成功', 'state' => 2,'url'=>$url]));
            }
        }
        echo "data:$frame->data\n";

    }
}