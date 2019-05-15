<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace ask\controllers;

use ask\controllers\Controller;

use common\components\Log;
use common\helpers\HuanxinHelper;
use common\helpers\HuanxinUserHelper;
use common\components\Code;
use common\components\HttpRequest;
use common\components\wx\WxBizDataCrypt;
use common\models\ArticleSend;
use common\models\AskChatRecord;
use common\models\AskChatRoom;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Login;
use common\models\Notice;
use common\models\Order;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;
use common\models\WxInfo;

class UserController extends Controller
{
    public function actionLogin($code)
    {
        //已登录
        if ($this->userLogin && $this->seaver_token) {
            $session_key = $this->seaver_token;
            $useridKey = md5($this->userid . "6623cXvY");
        } else {
            //获取用户微信登陆信息
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['ask_app_id'] . "&secret=" . \Yii::$app->params['ask_app_secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
            $userJson = $curl->get();
            $user = json_decode($userJson, true);
            if ($user['errcode']) {
                //获取用户微信登陆信息
                $path = "/sns/jscode2session?appid=" . \Yii::$app->params['ask_app_id'] . "&secret=" . \Yii::$app->params['ask_app_secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
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

            //更新用户平台id
            if ($user['unionid']) {

                $login = UserLogin::find()->andWhere(['unionid' => $user['unionid']])->one();
                $userid = $login ? $login->userid : 0;
                if ($login && !$login->aopenid) {
                    $login->aopenid = $user['openid'];
                    $login->unionid = $user['unionid'];
                    $login->save();
                }

                $this->userid = $login->userid;
                $this->user = $login->user;
                $this->userLogin = $login;
            }

            $useridKey = $userid ? md5($userid . "6623cXvY") : 0;
        }

        $order=Order::find()->select('id')->andWhere(['in','status',[1,2,3]])->andWhere(['userid'=>$this->userid])->column();
        $rooms=AskChatRoom::find()->select('id')->andWhere(['in','orderid',$order?$order:[]])->column();
        foreach($rooms as $k=>$v){
            $chats=AskChatRecord::find()->andWhere(['rid'=>$v])->andWhere(['is_read'=>0])->groupBy('createtime')->count();
            $chatRooms[$v]=$chats;
        }


        return ['sessionKey' => $session_key, 'userKey' => $useridKey,'rooms'=>$chatRooms];
    }
}