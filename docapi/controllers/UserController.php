<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace docapi\controllers;

use common\components\Code;
use common\components\HttpRequest;
use common\helpers\SmsSend;
use common\models\AskChatRecord;
use common\models\AskChatRoom;
use common\models\Login;
use common\models\Order;
use common\models\UserLogin;

class UserController extends Controller
{
    public function actionLogin($code,$phone,$vcode)
    {
        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
            return new Code(20010,'请填写正确手机号码！');
        }

//        if($vcode!=110112) {
//            $isVerify = SmsSend::verifymessage($phone, $vcode);
//            $isVerify = json_decode($isVerify, TRUE);
//            if ($isVerify['code'] != 200) {
//                return new Code(20010, '验证码填写错误！');
//            }
//        }
        $login=UserLogin::findOne(['phone'=>$phone,'type'=>1]);
        if($login) {
            //获取用户微信登陆信息
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['doctor_AppID'] . "&secret=" . \Yii::$app->params['doctor_AppSecret'] . "&js_code=" . $code . "&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
            $userJson = $curl->get();
            $user = json_decode($userJson, true);
            if ($user['errcode']) {
                //获取用户微信登陆信息
                $path = "/sns/jscode2session?appid=" . \Yii::$app->params['doctor_AppID'] . "&secret=" . \Yii::$app->params['doctor_AppSecret'] . "&js_code=" . $code . "&grant_type=authorization_code";
                $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
                $userJson = $curl->get();
                $user = json_decode($userJson, true);
                if ($user['errcode']) {
                    return new Code(30001, $user['errmsg']);
                }
            }
            $value = $user['openid'] . '@@' . $user['session_key'] . '@@' . $user['unionid'];

            //生成session key
            $cache = \Yii::$app->rdmp;
            $session_key = md5($value . time());
            $cache->set($session_key, $value);

            $login->dopenid = $user['openid'];
            $login->save();


            $useridKey = $login->userid ? md5($login->userid . "6623cXvY") : 0;
            return ['sessionKey' => $session_key, 'userKey' => $useridKey];
        }
        return new Code(20010,'手机号码未注册！');
    }
    /**
     * 发送验证码
     * @param $phone
     * @return Code
     */
    public function actionCode($phone){
        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
            return new Code(20010,'请填写正确手机号码！');
        }
        $sendData=SmsSend::sendSms($phone,'SMS_150575871');
    }


}