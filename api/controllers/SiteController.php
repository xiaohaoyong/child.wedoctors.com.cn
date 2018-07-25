<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 下午4:33
 */

namespace api\controllers;

use api\controllers\Controller;

use common\components\HttpRequest;

class SiteController extends \yii\web\Controller
{
    public function actionIndex(){
        $postStr = file_get_contents("php://input");
        $xml = simplexml_load_string($postStr, null, LIBXML_NOCDATA);
        $xmlArray=json_encode($xml);
        $xmlArray=json_decode($xmlArray,true);

        $template = <<<XML
 <xml>
     <ToUserName><![CDATA[%s]]></ToUserName>
     <FromUserName><![CDATA[%s]]></FromUserName>
     <CreateTime>%s</CreateTime>
     <MsgType><![CDATA[transfer_customer_service]]></MsgType>
 </xml>
XML;
        return sprintf($template, $xmlArray['FromUserName'], $xmlArray['ToUserName'], time());
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}