<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/7/27
 * Time: 下午3:03
 */

namespace console\models;


use jianyan\websocket\server\WebSocketServer;

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
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $this->_server->push($frame->fd, "this is server");
    }

}