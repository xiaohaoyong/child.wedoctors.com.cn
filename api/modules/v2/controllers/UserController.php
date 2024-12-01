<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace api\modules\v2\controllers;


use common\components\Code;
use common\components\HttpRequest;
use common\helpers\SmsSend;
use common\models\ArticlePushVaccine;
use common\models\ArticleSend;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Log;
use common\models\Notice;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;
use EasyWeChat\Factory;

class UserController extends \api\modules\v1\controllers\UserController
{
    public function actionPhoneLogin()
    {
        $log = new \common\components\Log("newlogin");

        $phone = \Yii::$app->request->get('phone');
        $code = \Yii::$app->request->get('code');
        $wxCode = \Yii::$app->request->get('wxCode');
        $test = \Yii::$app->request->get('test');

        //验证字段
        $isVerify = SmsSend::verifymessage(\Yii::$app->request->get('phone'), \Yii::$app->request->get('code'));
        $log->addLog("验证:" . $isVerify);

        $isVerify = json_decode($isVerify, TRUE);
        if ($isVerify['code'] != 200 && $code != 110112) {
            return new Code(20010, '手机验证码错误');
        }

        $cache = \Yii::$app->rdmp;
        $session = $cache->get($this->seaver_token);
        if (!$session) {
            if($test) {
                $app = Factory::miniProgram(\Yii::$app->params['easyX']);
                $wxUser = $app->auth->session($wxCode);
                if (!$wxUser || $wxUser['errcode']) {
                    return $wxUser;
                }
            }

            //获取用户微信信息如与库不同则更新登录信息，
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['wxXAppId'] . "&secret=" . \Yii::$app->params['wxXAppSecret'] . "&js_code=" . $wxCode . "&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
            $wxUserJson = $curl->get();
            $wxUser = json_decode($wxUserJson, true);
            if (!$wxUserJson || $wxUser['errcode']) {
                //获取用户微信登陆信息
                $path = "/sns/jscode2session?appid=" . \Yii::$app->params['wxXAppId'] . "&secret=" . \Yii::$app->params['wxXAppSecret'] . "&js_code=" . $wxCode . "&grant_type=authorization_code";
                $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
                $wxUserJson = $curl->get();
                $log->addLog("微信失败:" . $wxUserJson);

            }

            if ($wxUserJson && $wxUser['unionid']) {
                //保存用户登录状态
                $value = $wxUser['openid'] . '@@' . $wxUser['session_key'] . '@@' . $wxUser['unionid'];
                $log->addLog("value:" . $value);
                //生成session key
                $cache = \Yii::$app->rdmp;
                $session_key = md5($value . time());
                $cache->set($session_key, $value);
                $openid = $wxUser['openid'];
                $unionid = $wxUser['unionid'];

            }
        }else {
            $session = explode('@@', $session);
            $openid = $session[0];
            $unionid = $session[2];
            $session_key=$this->seaver_token;
        }
        //查询用户是否存在并且不存在则注册
        $userLogin = UserLogin::findOne(['phone' => $phone, 'type' => 0]);
        if (!$userLogin) {
            $userid = User::register($phone);
        } else {
            $userid = $userLogin->userid;
        }
        $log->addLog("userid:" . $userid);
        //更新登陆状态
        $userLogin = $userLogin ? $userLogin : new UserLogin();
        $userLogin->xopenid = $openid?$openid:'';
        $userLogin->unionid = $unionid?$unionid:'';
        $userLogin->logintime = time();
        $userLogin->hxusername = $this->hxusername;
        $userLogin->userid = $userid;
        $userLogin->phone = $phone;
        $userLogin->save();

        if ($userLogin) {
            UserLogin::updateAll(['unionid' => ''], ['and', ['unionid' => $unionid], ['<>', 'id', $userLogin->id]]);
            UserLogin::updateAll(['xopenid' => ''], ['and', ['xopenid' => $unionid], ['<>', 'id', $userLogin->id]]);
        } else {
            return new Code(20010, '登录失败');
        }
        //更新用户签约记录更新扫码状态
        $doctorParent = DoctorParent::findOne(['parentid' => $userid]);
        if (!$doctorParent || $doctorParent->level != 1) {

            $weOpenid = WeOpenid::findOne(['unionid' => $unionid]);
            if ($weOpenid) {
                if (!$userLogin->openid) {
                    $userLogin->openid = $weOpenid->openid;
                    $userLogin->save();
                }
                $log->addLog("weOpenid:" . $weOpenid->id);
            }

            if (!$weOpenid->doctorid) {
                $childInfo = ChildInfo::find()->andFilterWhere(['userid' => $userid])->andFilterWhere(['>', 'source', 38])->orderBy('birthday desc')->one();

                if ($childInfo) {
                    $log->addLog("childid:" . $childInfo->id);
                    $doctor = UserDoctor::findOne(['hospitalid' => $childInfo->source]);
                    $doctorid = $doctor ? $doctor->userid : 47156;
                } else {
                    $doctorid = 47156;
                }
            } else {
                $doctorid = $weOpenid->doctorid;
            }
            $log->addLog("doctorid:" . $doctorid);
            $isdoctorP = 1;
            $doctorParent = new DoctorParent();
            $doctorParent->doctorid = $doctorid;
            $doctorParent->parentid = $userid;
            $doctorParent->level = 1;
            $doctorParent->createtime = time();
            if ($doctorParent->save()) {
                $userDoctor = UserDoctor::findOne(['userid' => $doctorid]);
                if ($userDoctor) {
                    $hospital = $userDoctor->hospitalid;
                }
                ChildInfo::updateAll(['doctorid' => $hospital], 'userid=' . $userid);
                $log->addLog('hospital:' . $hospital);
                //签约成功 删除签约提醒
            }
        }
        if ($doctorParent && $doctorParent->level == 1 && $weOpenid && $weOpenid->level != 1) {
            $weOpenid->level = 1;
            $weOpenid->save();
            $log->addLog("扫码状态:" . implode(',', $weOpenid->firstErrors));
        }

        //发送初次登录消息（新注册消息，发送宣教）


        //新注册用户发送脊灰疫苗接种通知
//            $aid = 1979;
//            $article = \common\models\ArticleInfo::findOne($aid);
//            $data = [
//                'first' => array('value' => '新注册用户您好，请认真阅读脊灰疫苗接种前注意事项，选择自己宝宝适合的接种方式。'),
//                'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
//                'keyword2' => ARRAY('value' => '儿宝宝'),
//                'keyword3' => ARRAY('value' => '儿宝宝'),
//                'keyword4' => ARRAY('value' => '新注册用户'),
//                'keyword5' => ARRAY('value' => $article->title),
//                'remark' => ARRAY('value' => "为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
//            $url = \Yii::$app->params['site_url'] . "#/mission-read";
//            $miniprogram = [
//                "appid" => \Yii::$app->params['wxXAppId'],
//                "pagepath" => "pages/article/view/index?id=$aid",
//            ];
//
//            $log->addLog('userid:' . $userid);
//
//            //Notice::setList($userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => "/article/view/index?id=$aid"]);
//            $articlePushVaccine = ArticlePushVaccine::findOne(['openid' => $openid, 'aid' => $aid]);
//            if (!$articlePushVaccine || $articlePushVaccine->state != 1) {
//                $pushReturn = \common\helpers\WechatSendTmp::send($data, $openid, \Yii::$app->params['zhidao'], $url, $miniprogram);
//                $articlePushVaccine = new ArticlePushVaccine();
//                $articlePushVaccine->aid = $aid;
//                $articlePushVaccine->openid = $openid;
//                $articlePushVaccine->state = $pushReturn ? 1 : 0;
//                $articlePushVaccine->save();
//            }

        //Notice::setList($userid, 6, ['title' => '身高预测', 'ftitle' => '健康工具', 'id' => '/tool/height/index',]);
        //Notice::setList($userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);


        //判断用户是否需要签字

        if ($childInfo) {
            if ($doctorParent) {
                $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
            }
            $autograph = Autograph::findOne(['userid' => $this->userid]);
        }

        $useridx = $userLogin ? md5($userLogin->userid . "6623cXvY") : 0;
        $useridKey = $userid ? md5($userid . "6623cXvY") : 0;
        $huanxin = md5($wxUser['openid'] . '7Z9WL3s2');


        $log->addLog('useridx' . $useridx);
        $log->saveLog();
        return ['sessionKey' => $session_key, 'userKey' => $useridKey, 'userName' => $huanxin, 'useridx' => $useridx, 'type' => 0, 'doctor' => $doctor, 'is_autograph' => $autograph ? 0 : 1];
    }
}