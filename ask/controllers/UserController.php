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
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Notice;
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
            $login = UserLogin::findOne(['id'=>$this->userLogin->id]);
            $xopenid = $login->xopenid;
            $unionid = $login->unionid;
            $useridKey = md5($this->userid . "6623cXvY");
        } else {
            //获取用户微信登陆信息
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['ask_app_id'] . "&secret=" . \Yii::$app->params['ask_app_secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
            $userJson = $curl->get();
            $user = json_decode($userJson, true);
            if ($user['errcode'] == 40029) {
                return new Code(30001, $user['errmsg']);
            }
            $value = $user['openid'] . '@@' . $user['session_key'] . '@@' . $user['unionid'];

            //生成session key
            $cache = \Yii::$app->rdmp;
            $session_key = md5($value . time());
            $cache->set($session_key, $value);

            //更新用户平台id
            if ($user['unionid']) {

                //$userLogin=UserLogin::findOne(['unionid'=>$user['unionid']]);
                //$userLogin=UserLogin::find()->andFilterWhere(['or',['xopenid'=>$user['openid'],'unionid'=>$user['unionid']]])->one();
                //判断是否登录过问医生
                $userLogin = UserLogin::find()->andWhere(['and', ['unionid' => $user['unionid']]])->andWhere(['type'=>1])->one();
                if (!$userLogin) {
                    $userLogin=new UserLogin();
                    $Nuser=new User();
                    $Nuser->type=1;
                    $Nuser->source=1;
                    $Nuser->save();
                    $userid=$Nuser->id;
                }
                $weOpenid = WeOpenid::findOne(['unionid' => $user['unionid']]);
                if ($weOpenid->openid) {
                    $userLogin->openid = $weOpenid->openid;
                }
                $userLogin->userid=$userid;
                $userLogin->xopenid = $user['openid'];
                $userLogin->unionid = $user['unionid'];
                $userLogin->type=1;
                $userLogin->save();
            }

            $xopenid = $user['openid'];
            $useridKey = $userid ? md5($userid . "6623cXvY") : 0;
        }

        $huanxin = md5($xopenid . '7Z9WL3s2');
        $cache = \Yii::$app->rdmp;
        $cache->hset('firstLogin', $xopenid, time());

        return ['sessionKey' => $session_key, 'userKey' => $useridKey, 'userName' => $huanxin];

    }
}