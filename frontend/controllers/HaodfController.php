<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/2/21
 * Time: 下午3:48
 */

namespace frontend\controllers;


use common\models\WeOpenid;
use yii\web\Controller;
use EasyWeChat\Factory;

class HaodfController extends Controller
{
    public function actionIndex(){



        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $oauth = $app->oauth;
        if(\Yii::$app->request->get('code')) {
// 获取 OAuth 授权结果用户信息
            $user = $oauth->user();
            $userInfo=$user->toArray();
            if($userInfo['original']['openid']){
                $openid=$userInfo['original']['openid'];
                $weopenid=WeOpenid::findOne(['openid'=>$openid]);
                if($weopenid){
                    $timestamp = time();
                    $partnerKey="15f23dbae71f0f62";
                    $secret="EfPqDznSfV";
                    $params['partnerUserId']=$weopenid->id;
                    $signature = $this->generateSignature($secret, $timestamp, $partnerKey, $params);
                    $jumpUrl='https://m.haodf.com/ndynamic/coronalactivity/activity?businesstype=ebb';
                    $url= "https://m.haodf.com/openplatform/auth?partnerKey={$partnerKey}&timestamp={$timestamp}&signature={$signature}&partnerUserId={$params['partnerUserId']}jumpUrl={$jumpUrl}";
                    echo 123123123;exit;
                    $this->redirect($url);
                }
            }
        }
        exit;
    }
    public function generateSignature($secret, $timestamp, $partnerKey, $params) {
        $paramArray = array();
        foreach ($params as $param) {
            $paramArray[] = strval($param);
        }
        $paramArray[] = strval($secret);
        $paramArray[] = strval($timestamp);
        $paramArray[] = strval($partnerKey);
        sort($paramArray, SORT_STRING);
        $paramString = implode('', $paramArray);
        return sha1($paramString);
    }

}