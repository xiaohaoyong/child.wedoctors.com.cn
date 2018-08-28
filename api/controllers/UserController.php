<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace api\controllers;

use api\controllers\Controller;

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

    public function actionMessage()
    {
        $value = (string)time();
        return HuanxinHelper::setTxtMessage('wangzhentest', '72e2caa55d4934c1a74255550af7b76e', $value);
    }

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
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['wxXAppId'] . "&secret=" . \Yii::$app->params['wxXAppSecret'] . "&js_code=" . $code . "&grant_type=authorization_code";
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
                $weOpenid = WeOpenid::findOne(['unionid' => $user['unionid']]);
                if ($weOpenid) {
                    $weOpenid->xopenid = $user['openid'];
                    $weOpenid->save();
                }

                $login = UserLogin::find();
                if ($user['xopenid'] != '') {
                    $login->orWhere(['and', ['xopenid' => $user['openid']]]);
                }
                if ($user['unionid'] != '') {
                    $login->orWhere(['and', ['unionid' => $user['unionid']]]);
                }
                if ($user['unionid'] || $user['xopenid']) {
                    $userLogin = $login->one();
                }

                $userid = $userLogin ? $userLogin->userid : 0;
                if ($userLogin && !$userLogin->xopenid) {
                    if ($weOpenid->openid) {
                        $userLogin->openid = $weOpenid->openid;
                    }
                    $userLogin->xopenid = $user['openid'];
                    $userLogin->unionid = $user['unionid'];
                    $userLogin->save();
                }


            }

            $xopenid = $user['openid'];
            $useridKey = $userid ? md5($userid . "6623cXvY") : 0;


        }

        $huanxin = md5($xopenid . '7Z9WL3s2');
        HuanxinUserHelper::getUserInfo($huanxin);

        //对第一次登陆用户发送欢迎消息
        $cache = \Yii::$app->rdmp;
        $firstLogin = $cache->hget('firstLogin', $xopenid);
        if (!$firstLogin) {
            //HuanxinHelper::setTxtMessage('wangzhentest',$huanxin,'欢迎使用中医儿童健康管理工具');
        }
        $cache->hset('firstLogin', $xopenid, time());

        return ['sessionKey' => $session_key, 'userKey' => $useridKey, 'userName' => $huanxin];

    }

    public function actionWxUserInfo()
    {
        if ($this->userLogin) {
            $type = 0;
            $useridx = md5($this->userLogin->userid . "6623cXvY");
            $userLogin = UserLogin::findOne(['id'=>$this->userLogin->id]);
            $userLogin->logintime = time();
            $userLogin->save();
        } else {


            $appid = \Yii::$app->params['wxXAppId'];
            $cache = \Yii::$app->rdmp;
            $session = $cache->get($this->seaver_token);
            $session = explode('@@', $session);
            $openid = $session[0];
            $unionid = $session[2];

            $login = UserLogin::find();
            if ($openid != '') {
                $login->orWhere(['and', ['xopenid' => $openid]]);
            }
            if ($unionid != '') {
                $login->orWhere(['and', ['unionid' => $unionid]]);
            }
            if ($openid || $unionid) {
                $userLogin = $login->one();
            }
            if ($userLogin) {
                $type = 1;
                $useridx = md5($userLogin->userid . "6623cXvY");
                $userLogin->logintime = time();
                $userLogin->save();
            } else {

                //获取用户手机号
                $phoneEncryptedData = \Yii::$app->request->post('phoneEncryptedData');
                $phoneIv = \Yii::$app->request->post('phoneIv');
                $pc = new WxBizDataCrypt($appid, $session[1]);
                $code = $pc->decryptData($phoneEncryptedData, $phoneIv, $phoneJson);
                $phone = json_decode($phoneJson, true);

                if ($code == 0) {
                    $wephone = $phone['phoneNumber'];
                    $userLogin = UserLogin::findOne(['phone' => $wephone]);
                    if ($userLogin) {
                        $type = 2;
                        $useridx = md5($userLogin->userid . "6623cXvY");
                        $userLogin->xopenid = $openid;
                        $userLogin->unionid = $unionid;
                        $userLogin->logintime = time();
                        $userLogin->save();
                        $userid = $userLogin->userid;
                    } else {
                        $type = 3;
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
                    }
                }
            }

            //Notice::setList($userid, 6, ['title' => '身高预测', 'ftitle' => '健康工具', 'id' => '/tool/height/index',]);
            Notice::setList($userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);

            $userLogin = UserLogin::findOne(['userid' => $userid, 'phone' => $wephone]);
            $userLogin = $userLogin ? $userLogin : new UserLogin();

            $childInfo = ChildInfo::find()->andFilterWhere(['userid' => $userid])->andFilterWhere(['>', 'source', 38])->orderBy('birthday desc')->one();

            if($childInfo) {
                $doctor = UserDoctor::findOne(['hospitalid' => $childInfo->source]);
                $default = $doctor ? $doctor->userid : 47156;
                $doctorid = $default;
            }else{
                $doctorid=47156;
                $default=47156;
            }
//扫码签约
            $weOpenid = WeOpenid::findOne(['unionid' => $unionid]);
            if ($weOpenid) {
                $doctorid = $weOpenid->doctorid ? $weOpenid->doctorid : $default;
                $userLogin->openid = $weOpenid->openid;
            }
            $doctorParent = DoctorParent::findOne(['parentid' => $userid]);

            if (!$doctorParent || $doctorParent->level != 1) {
                $isdoctorP=1;
                $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                $doctorParent->doctorid = $doctorid;
                $doctorParent->parentid = $userid;
                $doctorParent->level = 1;
                $doctorParent->createtime = time();
                if ($doctorParent->save() && $weOpenid) {
                    $userDoctor = UserDoctor::findOne(['userid' => $doctorid]);
                    if ($userDoctor) {
                        $hospital = $userDoctor->hospitalid;
                    }
                    ChildInfo::updateAll(['doctorid' => $hospital], 'userid=' . $userid);

                    if ($weOpenid) {
                        $weOpenid->level = 1;
                        $weOpenid->save();
                    }
                    //签约成功 删除签约提醒
                }

            }

            //更新登陆状态
            $userLogin->xopenid = $openid;
            $userLogin->unionid = $unionid;
            $userLogin->logintime = time();
            $userLogin->userid = $userid;
            $userLogin->hxusername = $this->hxusername;
            $userLogin->phone = $wephone;
            $userLogin->save();
            $useridx = $userLogin ? md5($userLogin->userid . "6623cXvY") : 0;

            if($childInfo && $isdoctorP==1) {
                //发送最近宣教文章
                $articleSend = new ArticleSend();
                //$articleSend->artid=$av;
                $articleSend->childs[] = $childInfo;
                $articleSend->type = $childInfo->getType(1);
                $articleSend->doctorid = $doctorid;
                $articleSend->send('shouquan', false);
            }


        }
        return ['useridx' => $useridx, 'type' => $type];
    }

    public function actionWxInfo()
    {
        $params = \Yii::$app->request->post();

        if ($params) {
            $wxInfo = WxInfo::findOne(['loginid' => $this->userLogin->id]);
            $wxInfo = $wxInfo ? $wxInfo : new WxInfo();
            $params['userid'] = $this->userid;
            $params['loginid'] = $this->userLogin->id;
            $wxInfo->load(['WxInfo' => $params]);
            $wxInfo->save();
        } else {
            return new Code(20000, '失败');
        }
    }

}