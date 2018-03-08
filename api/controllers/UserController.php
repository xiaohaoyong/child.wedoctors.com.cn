<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace api\controllers;


use common\helpers\HuanxinHelper;
use common\helpers\HuanxinUserHelper;
use common\components\Code;
use common\components\HttpRequest;
use common\components\wx\WxBizDataCrypt;
use common\models\DoctorParent;
use common\models\User;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;
use common\models\WxInfo;

class UserController extends Controller
{

    public function actionMessage()
    {
        $value = (string)time();
        return HuanxinHelper::setTxtMessage('wangzhentest', '72e2caa55d4934c1a74255550af7b76e', $value);
    }

    public function actionLogin($code)
    {
        //已登录
        if($this->userid && $this->seaver_token){
            $session_key = $this->seaver_token;
            $login=UserLogin::findOne(['userid'=>$this->userid]);
            $xopenid=$login->xopenid;
            $unionid=$login->unionid;
            $useridKey= md5($this->userid."6623cXvY");
        }else{
            //获取用户微信登陆信息
            $path = "/sns/jscode2session?appid=".\Yii::$app->params['wxXAppId']."&secret=".\Yii::$app->params['wxXAppSecret']."&js_code=".$code."&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'].$path, true, 10);
            $userJson = $curl->get();
            $user = json_decode($userJson, true);
            $value = $user['openid'].'@@'.$user['session_key'].'@@'.$user['unionid'];

            //生成session key
            $cache = \Yii::$app->rdmp;
            $session_key = md5($value.time());
            $cache->set($session_key, $value);

            //更新用户平台id
            if ($user['unionid']) {
                $weOpenid = WeOpenid::findOne(['unionid' => $user['unionid']]);
                if (!$weOpenid) {
                    $weOpenid = new WeOpenid();
                    $weOpenid->unionid = $user['unionid'];
                }
                $weOpenid->xopenid = $user['openid'];
                $weOpenid->save();
            }

            $xopenid=$user['openid'];
            $useridKey=0;


        }

        $huanxin = md5($xopenid.'7Z9WL3s2');
        HuanxinUserHelper::getUserInfo($huanxin);

        //对第一次登陆用户发送欢迎消息
        $cache = \Yii::$app->rdmp;
        $firstLogin=$cache->hget('firstLogin',$xopenid);
        if(!$firstLogin){
            HuanxinHelper::setTxtMessage('wangzhentest',$huanxin,'欢迎使用中医儿童健康管理工具');
        }
        $cache->hset('firstLogin',$xopenid,time());

        return ['sessionKey' => $session_key, 'userKey' => $useridKey, 'userName' => $huanxin];

    }

    public function actionWxUserInfo()
    {
        $phoneEncryptedData = \Yii::$app->request->post('phoneEncryptedData');
        $phoneIv = \Yii::$app->request->post('phoneIv');


        $appid = \Yii::$app->params['wxXAppId'];
        $cache = \Yii::$app->rdmp;
        $session = $cache->get($this->seaver_token);
        $session = explode('@@', $session);
        $pc = new WxBizDataCrypt($appid, $session[1]);
        $code = $pc->decryptData($phoneEncryptedData, $phoneIv, $phoneJson);


        $phone = json_decode($phoneJson, true);
        $openid = $session[0];
        $unionid = $session[2];

        $wephone=$phone;
        if ($code == 0) {
            $user = User::findOne(['phone' => $wephone]);
            if (!$user) {
                $userParent = UserParent::find()->where(['mother_phone' => $wephone])->orFilterWhere(['father_phone' => $wephone])->orFilterWhere(['field12' => $wephone])->one();
                if ($userParent) {
                    $userid = $userParent->userid;
                }
            } else {
                $userid = $user->id;
            }
            //注册

            if (!$userid) {
                $user = new User();
                $user->phone = $wephone;
                $user->level = 0;
                $user->type = 1;
                $user->save();
                $userid = $user->id;
            }


            $userLogin = UserLogin::findOne(['userid' => $userid]);
            $userLogin = $userLogin ? $userLogin : new UserLogin();

            //扫码签约
            $weOpenid = WeOpenid::findOne(['unionid' => $unionid, 'level' => 0]);
            if ($weOpenid) {
                $doctorid = $weOpenid->doctorid;
                $doctorParent = DoctorParent::findOne(['doctorid' => $doctorid, "parentid" => $userid]);
                $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                $doctorParent->doctorid = $doctorid;
                $doctorParent->parentid = $userid;
                $doctorParent->level = 1;
                $doctorParent->createtime = time();
                if ($doctorParent->save()) {
                    $weOpenid->level = 1;
                    $weOpenid->save();
                    //签约成功 删除签约提醒
                }
                $userLogin->openid = $weOpenid->openid;
            }
            //更新登陆状态
            $userLogin->xopenid = $openid;
            $userLogin->unionid = $unionid;
            $userLogin->logintime = time();
            $userLogin->userid = $userid;
            $userLogin->hxusername=$this->hxusername;
            $userLogin->save();

            $useridx = $userLogin ? md5($userLogin->userid."6623cXvY") : 0;


            $doctorParent=DoctorParent::findOne(['parentid'=>$userid]);
            if($doctorParent->doctorid) {
                $doctorLogin=UserLogin::findOne(['userid'=>$doctorParent->doctorid]);
                return HuanxinHelper::setTxtMessage($doctorLogin->hxusername, $userLogin->hxusername, '恭喜你，已成功签约儿保顾问，育儿保健问题可以问我哦！医生在线时回给您回复~');
            }


            return $useridx;
        }
        return new Code(20010, '失败');
    }

    public function actionWxInfo()
    {
        $cache = \Yii::$app->rdmp;
        $session = $cache->get($this->seaver_token);
        $session = explode('@@', $session);
        $openid = $session[0];

        $encryptedData = \Yii::$app->request->post('encryptedData');
        $iv = \Yii::$app->request->post('iv');
        $appid = \Yii::$app->params['wxXAppId'];
        $pc = new WxBizDataCrypt($appid, $session[1]);
        $code1 = $pc->decryptData($encryptedData, $iv, $userJson);
        $user = json_decode($userJson, true);

        $wxInfo = WxInfo::findOne(['openid' => $openid]);
        if (!$wxInfo && $this->userid) {
            $wxInfo = new WxInfo();



            $curl = new HttpRequest($user['avatarUrl'], true, 2);
            $wxImg = $curl->get();
            $type = getimagesize($user['avatarUrl']);
            switch ($type[2]) {//判读图片类型
                case 1:
                    $img_type = "gif";
                    break;
                case 2:
                    $img_type = "jpg";
                    break;
                case 3:
                    $img_type = "png";
                    break;
            }

            $time = time();
            $filename = substr(md5($time), 4, 14).".".$img_type;
            if (file_put_contents(__ROOT__."/../../".\Yii::$app->params['imageDir']."/upload/".$filename, $wxImg)) {
                $img = \Yii::$app->params['imageUrl'].$filename;
            }

            $wxInfo->openid = $openid;
            $wxInfo->name = $user['nickName'];
            $wxInfo->img = $img;
            $wxInfo->userid = $this->userid ? $this->userid : 0;
            $wxInfo->save();
        }else{
            $wxInfo['img']=$user['avatarUrl'];
            $wxInfo['name'] = $user['nickName'];

        }


        return $wxInfo;
    }

}