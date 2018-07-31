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
        if($this->_type == 'ws')
        {
            $this->_server = new \swoole_websocket_server($this->_host, $this->_port, $this->_mode, $this->_socketType);
        }
        else
        {
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
        $data=json_decode($frame->data,true);
        if($data['type']=='Apply') {
            $dataUser=DataUser::findOne(['token'=>$data['token']]);

            if($dataUser) {
                $dataUserTask = DataUserTask::findOne(['datauserid' => $dataUser->id, 'state' => 0]);
                if ($dataUserTask) {
                    echo "更新任务";
                    $dataUserTask->fd=$frame->fd;
                    $dataUserTask->save();
                    $this->_server->push($frame->fd, json_encode(['type' => 'Apply', 'Result' => '成功', 'state' => 3]));
                } else {
                    echo "创建任务";
                    //创建任务
                    $dataUserTask = new DataUserTask();
                    $dataUserTask->createtime = time();
                    $dataUserTask->datauserid = $dataUser->id;
                    $dataUserTask->note=str_replace('ChildInfoSearchModel','',$data['data']);
                    $dataUserTask->fd=$frame->fd;
                    $dataUserTask->save();
                    $data['task_id']=$dataUserTask->id;
                    $server->task($data);
                    echo "结束";
                }
            }else{
                $this->_server->push($frame->fd, json_encode(['type' => 'Apply', 'Result' => '失败', 'state' => 0]));
            }

        }
    }
    public function onTask($server, $task_id, $from_id, $data)
    {
        echo "开始任务\n";
        $server->finish($data);
    }

    public function onFinish($server, $task_id, $data)
    {
        $dataUser=DataUser::findOne(['token'=>$data['token']]);
        $dataUserTask = DataUserTask::findOne(['datauserid' => $dataUser->id, 'state' => 0]);

        $childDow=new ChildDown();
        $childDow->_server=$server;
        $childDow->_server_fd=$dataUserTask->fd;



        parse_str(urldecode($data['data']),$postData);
        $childDow->setData($postData,$dataUser);
        $filename=$childDow->excel();


        $url=Html::a('点击下载','http://static.wedoctors.com.cn/'.$filename,['target'=>"_blank"]);
        $server->push($childDow->_server_fd, json_encode(['type' => 'Apply', 'Result' => '成功', 'state' => 2,'url'=>$url]));    }

}