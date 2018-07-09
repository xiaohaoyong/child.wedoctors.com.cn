<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/23
 * Time: 下午4:33
 */

namespace api\controllers;


use common\components\HttpRequest;

class SiteController extends \yii\web\Controller
{
    public function actionIndex(){

        return $_GET['echostr'];
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