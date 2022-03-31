<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace api\modules\v2\controllers;


use common\components\Code;
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

class UserController extends \api\modules\v1\controllers\UserController
{
    public function actionPhoneLogin()
    {
        $log = new \common\components\Log("newlogin");

        $phone = \Yii::$app->request->get('phone');
        $code = \Yii::$app->request->get('code');

        $isVerify = SmsSend::verifymessage(\Yii::$app->request->get('phone'), \Yii::$app->request->get('code'));
        $isVerify = json_decode($isVerify, TRUE);
        if ($isVerify['code'] != 200 && $code != 110112) {
            return new Code(20010, '手机验证码错误');
        }

        $cache = \Yii::$app->rdmp;
        $session = $cache->get($this->seaver_token);
        if (!$session) {
            return new Code(20010, '登录失败，请重新进入小程序后登录！');
        }
        $session = explode('@@', $session);
        $openid = $session[0];
        $unionid = $session[2];

        $userLogin = UserLogin::findOne(['phone' => $phone, 'type' => 0]);
        if (!$userLogin) {
            $user = User::findOne(['phone' => $phone]);
            if (!$user) {
                $userParent1 = UserParent::find()->where(['mother_phone' => $phone])->one();
                $userParent2 = UserParent::find()->where(['father_phone' => $phone])->one();
                $userParent3 = UserParent::find()->where(['field12' => $phone])->one();

                if ($userParent1) {
                    $userid = $userParent1->userid;
                } elseif ($userParent2) {
                    $userid = $userParent2->userid;

                } elseif ($userParent3) {
                    $userid = $userParent3->userid;
                }
            } else {
                $userid = $user->id;
            }
            //注册
            if (!$userid) {
                $user = new User();
                $user->phone = $phone;
                $user->level = 0;
                $user->type = 1;
                $user->save();
                $userid = $user->id;
            }
            $aid=1979;
            $article=\common\models\ArticleInfo::findOne($aid);
            $data = [
                'first' => array('value' => '新注册用户您好，请认真阅读脊灰疫苗接种前注意事项，选择自己宝宝适合的接种方式。'),
                'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                'keyword2' => ARRAY('value' => '儿宝宝'),
                'keyword3' => ARRAY('value' => '儿宝宝'),
                'keyword4' => ARRAY('value' => '新注册用户'),
                'keyword5' => ARRAY('value' => $article->title),
                'remark' => ARRAY('value' => "为了您宝宝健康，请仔细阅读哦。", 'color' => '#221d95'),];
            $url = \Yii::$app->params['site_url'] . "#/mission-read";
            $miniprogram = [
                "appid" => \Yii::$app->params['wxXAppId'],
                "pagepath" => "pages/article/view/index?id=$aid",
            ];

            Notice::setList($userid, 3, ['title' =>  $article->title, 'ftitle' => '新注册用户', 'id' => "/article/view/index?id=$aid"]);
            $articlePushVaccine=ArticlePushVaccine::findOne(['openid'=>$openid,'aid'=>$aid]);
            if(!$articlePushVaccine || $articlePushVaccine->state!=1) {
                $pushReturn = \common\helpers\WechatSendTmp::send($data, $openid, \Yii::$app->params['zhidao'], $url, $miniprogram);
                $articlePushVaccine = new ArticlePushVaccine();
                $articlePushVaccine->aid = $aid;
                $articlePushVaccine->openid = $openid;
                $articlePushVaccine->state = $pushReturn?1:0;
                $articlePushVaccine->save();
            }



        } else {
            $userid = $userLogin->userid;
        }
        //更新登陆状态
        $userLogin = $userLogin ? $userLogin : new UserLogin();
        $userLogin->xopenid = $openid;
        $userLogin->unionid = $unionid;
        $userLogin->logintime = time();
        $userLogin->hxusername = $this->hxusername;
        $userLogin->userid = $userid;
        $userLogin->phone = $phone;
        if ($userLogin->save()) {
            UserLogin::updateAll(['unionid' => ''], ['and', ['unionid' => $unionid], ['<>', 'id', $userLogin->id]]);
            UserLogin::updateAll(['xopenid' => ''], ['and', ['xopenid' => $unionid], ['<>', 'id', $userLogin->id]]);
            //Notice::setList($userid, 6, ['title' => '身高预测', 'ftitle' => '健康工具', 'id' => '/tool/height/index',]);
            Notice::setList($userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);
            $doctorParent = DoctorParent::findOne(['parentid' => $userid]);
            $weOpenid = WeOpenid::findOne(['unionid' => $unionid]);
            if ($weOpenid) {
                if(!$userLogin->openid) {
                    $userLogin->openid = $weOpenid->openid;
                    $userLogin->save();
                }
                $log->addLog("weOpenid:" . $weOpenid->id);
            }
            if (!$doctorParent || $doctorParent->level != 1) {

                $childInfo = ChildInfo::find()->andFilterWhere(['userid' => $userid])->andFilterWhere(['>', 'source', 38])->orderBy('birthday desc')->one();

                if ($childInfo) {
                    $log->addLog("childid:" . $childInfo->id);
                    $doctor = UserDoctor::findOne(['hospitalid' => $childInfo->source]);
                    $default = $doctor ? $doctor->userid : 47156;
                    $doctorid = $default;
                } else {
                    $doctorid = 47156;
                    $default = 47156;
                }
//扫码签约
                $log->addLog("doctorid:" . $doctorid);

                $doctorid = $weOpenid->doctorid ? $weOpenid->doctorid : $default;

                $log->addLog("未签约");
                $isdoctorP = 1;
                $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                $doctorParent->doctorid = $doctorid;
                $doctorParent->parentid = $userid;
                $doctorParent->level = 1;
                $doctorParent->createtime = time();
                if ($doctorParent->save()) {
                    $log->addLog("签约:" . implode(',', $doctorParent->firstErrors));
                    $userDoctor = UserDoctor::findOne(['userid' => $doctorid]);
                    if ($userDoctor) {
                        $hospital = $userDoctor->hospitalid;
                    }
                    ChildInfo::updateAll(['doctorid' => $hospital], 'userid=' . $userid);
                    $log->addLog('doctorid:' . $hospital);
                    //签约成功 删除签约提醒
                }
            }
            if ($doctorParent && $doctorParent->level == 1 && $weOpenid) {
                $weOpenid->level = 1;
                $weOpenid->save();
                $log->addLog("扫码状态:" . implode(',', $weOpenid->firstErrors));
            }


            if ($childInfo && $isdoctorP == 1) {
                //发送最近宣教文章
                $articleSend = new ArticleSend();
                //$articleSend->artid=$av;
                $articleSend->childs[] = $childInfo;
                $articleSend->type = $childInfo->getType(1);
                $articleSend->doctorid = $doctorid;
                $articleSend->send('shouquan', false);
            }
        } else {
            var_dump($userLogin->firstErrors);
            exit;
        }
        $useridx = $userLogin ? md5($userLogin->userid . "6623cXvY") : 0;


        $log->addLog('useridx' . $useridx);

        $log->saveLog();

        if ($childInfo) {
            if ($doctorParent) {
                $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
            }
            $autograph = Autograph::findOne(['userid' => $this->userid]);
        }
        return ['useridx' => $useridx, 'type' => 0, 'doctor' => $doctor, 'is_autograph' => $autograph ? 0 : 1];
    }
}