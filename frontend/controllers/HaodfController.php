<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/2/21
 * Time: 下午3:48
 */

namespace frontend\controllers;


use common\models\UserLogin;
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
                    $userLoign=UserLogin::findOne(['openid'=>$openid]);
                    if($userLoign && $userLoign->phone){
                        $params['mobile']=$userLoign->phone;
                        $mobile=urlencode($this->phoneMd5($partnerKey,$secret,$userLoign->phone));
                        $mobileUrl="&mobile={$mobile}";
                    }

                    $signature = $this->generateSignature($secret, $timestamp, $partnerKey, $params);
                    $jumpUrl=urlencode('https://m.haodf.com/ndynamic/coronalactivity/activity?businesstype=ebb');
                    $url= "https://m.haodf.com/openplatform/authForHealthpal?partnerKey={$partnerKey}&timestamp={$timestamp}&signature={$signature}&partnerUserId={$params['partnerUserId']}{$mobileUrl}&jumpUrl={$jumpUrl}";
                    return $this->redirect($url);
                }
            }
        }
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
    public function phoneMd5($partnerKey,$secret,$phone){
        $secretAccessKey=substr(md5($partnerKey.$secret),8,16);
        $crypted = openssl_encrypt($phone, 'AES-128-ECB', $secretAccessKey);
        return $crypted;
    }
}