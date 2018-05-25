<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/25
 * Time: 下午3:03
 */

namespace console\controllers;


use yii\console\Controller;

class SocketController extends Controller
{
    public $socs;
    public $soc;
    public $hand;

    public function actionServer()
    {
        //创建一个套接字
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //设置套接字选项
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        //绑定IP地址和端口
        socket_bind($socket, "127.0.0.1", 8081);
        //监听套接字
        socket_listen($socket);
        $this->soc = $socket;
        $this->socs = array($socket);
        //挂起进程
        while (true) {
            $arr = $this->socs;
            $write = $except = NULL;
            //接收套接字数字 监听他们的状态
            socket_select($arr, $write, $except, NULL);
            //遍历套接字数组
            foreach ($arr as $k => $v) {
                var_dump($v);
                //如果是新建立的套接字返回一个有效的 套接字资源
                if ($this->soc == $v) {
                    $client = socket_accept($this->soc);
                    if ($client < 0) {
                        echo "socket_accept() failed";
                    } else {
                        // array_push($this->socs,$client);
                        // unset($this[]);
                        //将有效的套接字资源放到套接字数组
                        socket_getpeername($client, $addr, $port);  //获取远程客户端ip地址和端口
                        echo "client connect server: ip = $addr, port = $port" . PHP_EOL;
                        $this->socs[] = $client;
                    }
                } else {
                    //从已连接的socket接收数据  返回的是从socket中接收的字节数
                    $byte = @socket_recv($v, $buff, 20480, 0);
                    //如果接收的字节是0
                    if ($byte < 7) {
                        socket_close($v);
                        //移除对该 socket 监听
                        foreach ($this->socs as $key => $val)
                        {
                            if ($val == $v) unset($this->socs[$key]);
                        }
                        continue;
                    }
                    //判断有没有握手没有握手则进行握手,如果握手了 则进行处理
                    if (!$this->hand[(int)$client]) {
                        echo "hand\n";
                        //进行握手操作
                        $this->hands($client, $buff, $v);
                    } else {
                        echo "data\n";
                        var_dump($buff);
                        //处理数据操作
                        $mess = $this->decodeData($buff);
                        //发送数据
                        var_dump($mess);
                        $this->send($mess, $v);
                    }
                }
            }
        }


        return $socket;
    }

    public function hands($client, $buff, $v)
    {
        //提取websocket传的key并进行加密  （这是固定的握手机制获取Sec-WebSocket-Key:里面的key）
        $buf = substr($buff, strpos($buff, 'Sec-WebSocket-Key:') + 18);
        //去除换行空格字符
        $key = trim(substr($buf, 0, strpos($buf, "\r\n")));
        //固定的加密算法
        $new_key = base64_encode(sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
        $new_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $new_message .= "Upgrade: websocket\r\n";
        $new_message .= "Sec-WebSocket-Version: 13\r\n";
        $new_message .= "Connection: Upgrade\r\n";
        $new_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
        //将套接字写入缓冲区
        socket_write($v, $new_message, strlen($new_message));
        // socket_write(socket,$upgrade.chr(0), strlen($upgrade.chr(0)));
        //标记此套接字握手成功
        $this->hand[(int)$client] = true;
    }

    public function decodeData($buff)
    {
        //$buff  解析数据帧
        $mask = array();
        $data = '';
        $msg = unpack('H*', $buff);  //用unpack函数从二进制将数据解码
        $head = substr($msg[1], 0, 2);
        if (hexdec($head{1}) === 8) {
            $data = false;
        } else if (hexdec($head{1}) === 1) {
            $mask[] = hexdec(substr($msg[1], 4, 2));
            $mask[] = hexdec(substr($msg[1], 6, 2));
            $mask[] = hexdec(substr($msg[1], 8, 2));
            $mask[] = hexdec(substr($msg[1], 10, 2));
            //遇到的问题  刚连接的时候就发送数据  显示 state connecting
            $s = 12;
            $e = strlen($msg[1]) - 2;
            $n = 0;
            for ($i = $s; $i <= $e; $i += 2) {
                $data .= chr($mask[$n % 4] ^ hexdec(substr($msg[1], $i, 2)));
                $n++;
            }
            //发送数据到客户端
            //如果长度大于125 将数据分块
            $block = str_split($data, 125);
            $mess = array(
                'mess' => $block[0],
            );
            return $mess;
        }
    }


    public function send($mess, $v)
    {
        //遍历套接字数组 成功握手的  进行数据群发
        foreach ($this->socs as $keys => $values) {
            //用系统分配的套接字资源id作为用户昵称
            $mess['name'] = "Tourist's socket:{$v}";
            $str = json_encode($mess);
            $writes = "\x81" . chr(strlen($str)) . $str;
            // ob_flush();
            // flush();
            // sleep(3);
            if ($this->hand[(int)$values])
                socket_write($values, $writes, strlen($writes));
        }
    }
}