<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/7/27
 * Time: 下午3:03
 */

namespace console\models;


use common\components\Log;
use common\helpers\Im;
use common\models\AskChatRecord;
use common\models\AskChatRecordView;
use common\models\AskChatRoom;
use common\models\DataUser;
use common\models\DataUserTask;
use common\models\Order;
use common\models\UserLogin;
use console\controllers\ChildExcController;
use http\Url;
use jianyan\websocket\server\WebSocketServer;
use yii\helpers\Html;

class ImServer extends WebSocketServer
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

    public function onOpen($server, $frame)
    {
        //用户上线
        if ($frame->header->sessionkey) {
            $userLogin = self::userLogin($frame->header->sessionkey);
            if ($userLogin) {
                //在线状态
                \Yii::$app->rdmp->zadd('ask_im_login', $frame->fd, $userLogin->userid);
            }
        }
        parent::onOpen($server, $frame); // TODO: Change the autogenerated stub
    }

    public function onMessage($server, $frame)
    {
        $obj = json_decode($frame->data);
        $userLogin = self::userLogin($obj->sessionkey);

        if ($obj->type == 'msg') {
            $tofd = 0;
            $touserid = 0;
            if ($obj->ext->touser) {
                $tofd = \Yii::$app->rdmp->ZSCORE('ask_im_login', $obj->ext->touser);
                $touserid = $obj->ext->touser;
            } elseif ($obj->ext->roomid) {
                $room = AskChatRoom::findOne(['id' => $obj->ext->roomid]);
                if ($room->doctorid) {
                    $tofd = \Yii::$app->rdmp->ZSCORE('ask_im_login', $room->doctorid);
                    $touserid = $room->doctorid;
                }
            }


            //如果用户在线则直接发送
            if ($tofd) {
                $value = (array)$obj;
                unset($value['sessionkey']);
                $server->push($tofd, json_encode($value));
            }

            if ($obj->ext->msgType == 'text') {
                //保存聊天内容
                //分割内容
                $contents = str_split($obj->ext->content, 190);
                $time = time();
                foreach ($contents as $k => $v) {
                    $chat = new AskChatRecord();
                    $chat->userid = $userLogin->userid;
                    $chat->touserid = $touserid;
                    $chat->rid = $obj->ext->roomid;
                    $chat->createtime = $time;
                    $chat->type = 1;
                    if ($chat->save()) {
                        $chatView = new AskChatRecordView();
                        $chatView->record_id = $chat->id;
                        $chatView->content = $v;
                        $chatView->save();
                    }
                }
            } elseif ($obj->ext->msgType == 'img') {
                $time = time();
                $chat = new AskChatRecord();
                $chat->userid = $userLogin->userid;
                $chat->touserid = $touserid;
                $chat->rid = $obj->ext->roomid;
                $chat->createtime = $time;
                $chat->type = 2;
                if ($chat->save()) {
                    $chatView = new AskChatRecordView();
                    $chatView->record_id = $chat->id;
                    $chatView->content = $obj->ext->msgExt->file;
                    $chatView->save();
                }

            }elseif ($obj->ext->msgType == 'close'){

                $room=AskChatRoom::findOne(['id'=>$obj->ext->roomid]);
                if($room){
                    $order=Order::findOne(['id'=>$room->orderid]);
                    if($order){
                        $order->status=4;
                        $order->save();
                    }
                }
            }
        }
        //$server->push($frame->fd, '{"type":"msg","ext":{"toUser":"team","roomid":2,"msgType":"text","content":"dsasdfa","msgExt":{"file":""}}}');
        echo "data:$frame->data\n";
    }

    public function onClose($server, $fd)
    {
        $users = \Yii::$app->rdmp->ZRANGEBYSCORE('ask_im_login', $fd, $fd);
        foreach ($users as $k => $v) {
            \Yii::$app->rdmp->ZREM('ask_im_login', $v);
            echo "time:" . time() . "用户" . $v . "下线,频道:" . $fd . "\n";
        }
        parent::onClose($server, $fd); // TODO: Change the autogenerated stub
    }

    public static function userLogin($seaver_token)
    {
        $cache = \Yii::$app->rdmp;
        $session = $cache->get($seaver_token);
        if ($session) {
            $session = explode('@@', $session);
            $userLogin = UserLogin::findOne(['aopenid' => $session[0]]);
            return $userLogin;
        }
    }
}