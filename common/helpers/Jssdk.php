<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2023/5/26
 * Time: 20:04
 */

namespace common\helpers;


use common\components\HttpRequest;

class Jssdk {
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = [
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        ];
        return json_encode($signPackage);
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $this->get_php_file("jsapi_ticket.php");
        if ($data) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $curl = new HttpRequest($url, true, 10);
            $userJson = $curl->get();
            $res = json_decode($userJson);
            $ticket = $res->ticket;
            if ($ticket) {
                $this->set_php_file("jsapi_ticket.php", $ticket);
            }
        } else {
            $ticket = $data;
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $this->get_php_file("access_token.php");
        if (!$data) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $curl = new HttpRequest($url, true, 10);
            $userJson = $curl->get();
            $res = json_decode($userJson);
            $access_token = $res->access_token;
            if ($access_token) {
                $this->set_php_file("access_token.php", $access_token);
            }
        } else {
            $access_token = $data;
        }
        return $access_token;
    }

    private function get_php_file($filename) {
        $redis=\Yii::$app->rdmp;
        $redis->get($filename);

    }
    private function set_php_file($filename, $content) {
        $redis=\Yii::$app->rdmp;
        $redis->psetex($filename, time() + 7000, $content);
    }
}