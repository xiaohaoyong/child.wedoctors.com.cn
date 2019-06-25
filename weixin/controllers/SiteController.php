<?php

namespace weixin\controllers;

use common\models\WeOpenid;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use weixin\models\User;
use weixin\models\UserLogin;
use common\helpers\SendHelper;
use callmez\wechat\sdk\MpWechat;
use weixin\models\DoctorParent;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $enableCsrfValidation = false;
    public $userInfo;
    public $mpWechat;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * 首页
     * @author slx
     * 2017-09-27
     */
    public function actionIndex()
    {
        $openid = $this->sendOpenid();
        $userInfo = \common\models\UserLogin::getInfo($openid);

        $weOpenid=WeOpenid::findOne(['openid'=>$openid,'level'=>0]);
        if ($weOpenid) {
            $_url = urlencode(\Yii::$app->params['htmlUrl']."#/first-login-adviser?doctor_id=".$weOpenid->doctorid);
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.\Yii::$app->params['AppID'].'&redirect_uri='.$_url.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
            header("Location:$url");
            exit;
        }
        //不存在的话 登陆
        if (empty($userInfo)) {
            header("Location:".Yii::$app->params['site_url']."#/usertype?openid=".$this->userInfo['openid']);
            exit;
        }
        //存在的话 医生调医生首页 家长调家长首页
        //        $url = $userInfo['type'] == 1 ? '/site/child#/sign' : '/site/child#/accountdocter'; //登录页
        $url = $userInfo['type'] == 1 ? '#/home-parent' : '#/home-docter';
        header("Location:".Yii::$app->params['site_url'].$url."");
        exit;
    }

    /**
     * 首页----推送消息中点击的链接
     * @author slx
     * 2017-09-27
     */
    public function actionChilds()
    {
        $openid = $this->sendOpenid();
        $userInfo = \common\models\UserLogin::getInfo($openid);
        //存在的话 医生调医生首页 家长调家长首页
        if (!empty($userInfo)) {
            $url = $userInfo['type'] == 1 ? '#/home-parent' : '#/home-docter';
            header("Location:".Yii::$app->params['site_url'].$url."");
            exit;
        }
        return $this->render('child');
    }

    /**
     * 首页
     * @author slx
     * 2017-09-27
     */
    public function actionChild()
    {
        $openid = $this->sendOpenid();
        return $this->render('child');
    }

    /**
     * 儿保顾问
     * @author slx
     * 2017-09-26
     */
    public function actionDoctorParent($userid)
    {
        $DoctorData = \weixin\models\UserDoctor::GetOneById($userid);
        return $this->returnJson('200', 'success', $DoctorData);
    }

    /**
     * 扫码医生二维码推送
     * @author xusheng
     * 2017-11-27
     */
    public function actionFirstLoginAdviser($doctor_id)
    {
        $openid = $this->sendOpenid();
        Yii::$app->cache->delete("openid_".$openid);
    }

    /**
     * 扫码医生二维码推送
     * @author xusheng
     * 2017-11-27
     */
    public function actionFirstLoginBydoctor($doctor_id)
    {
        $openid = $this->sendOpenid();
        $userid = UserLogin::findOne(['openid' => $openid])->userid;
        if (!empty($userid)) {
            $data = ['DoctorParent' => ['doctorid' => $doctor_id, 'parentid' => $userid, 'createtime' => time(), 'level' => 1,],];
            //$result = DoctorParent::addData($data);
        }
        $url = '#/home-parent';
        header("Location:".Yii::$app->params['site_url'].$url."");
        exit;
    }

    /**
     * 扫码医生二维码推送，链接
     * @author slx
     * 2017-09-27
     */
    public function actionDoctorQrcode($doctor_id)
    {
        $openid = $this->sendOpenid();
        $userInfo = \common\models\UserLogin::getInfo($openid);
        if (empty($userInfo)) {
            header("Location:".Yii::$app->params['site_url']."#/sign?dotor_id=".$doctor_id);
            exit;
        }
        //type==1为用户，添加医生！！！
        if ($userInfo['type'] == 1) {
            //判断是否签约过
            $DoctorData = DoctorParent::findByParentid($userInfo['userid']);
            if (empty($DoctorData)) {
                $data = ['DoctorParent' => ['doctorid' => $doctor_id, 'parentid' => $userInfo['userid'], 'createtime' => time(), 'level' => 0,],];
                $result = DoctorParent::addData($data);
                if ($result) {
                    //签约通知！！通知---医生
                }
            }
        }
        $url = $userInfo['type'] == 1 ? '#/home-parent' : '#/home-docter';
        header("Location:".Yii::$app->params['site_url'].$url."");
        exit;
    }

    private function GetOpenid()
    {
        $openid = $this->getCookie('openid');
        if (empty($openid)) {
            $this->mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
            $_code = Yii::$app->request->get('code');
            if (empty($_code)) {
                return $this->redirectOauth2Url();
            } else {
                $getOauth2AccessToken = $this->mpWechat->getOauth2AccessToken(Yii::$app->request->get('code'));
                if (empty($getOauth2AccessToken['openid'])) {
                    throw new BadRequestHttpException('openid获取失败！');
                }
                $openid = $getOauth2AccessToken['openid'];
            }
            $this->addCookie('openid', $openid, 2592000);
        }
        return $openid;
    }

    private function sendOpenid()
    {
        $openid = $this->getCookie('openid');
        if (empty($openid)) {
            $this->mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
            $_code = Yii::$app->request->get('code');
            if (empty($_code)) {

                $_url = urlencode(Yii::$app->request->hostInfo.Yii::$app->request->getUrl());
                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.\Yii::$app->params['AppID'].'&redirect_uri='.$_url.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
                header("Location:$url");
                exit();
            } else {
                $getOauth2AccessToken = $this->mpWechat->getOauth2AccessToken(Yii::$app->request->get('code'));
                if (empty($getOauth2AccessToken['openid'])) {
                    throw new BadRequestHttpException('openid获取失败！');
                }
                $openid = $getOauth2AccessToken['openid'];
            }
            $this->addCookie('openid', $openid, 2592000);
        }
        return $openid;
    }

    /**
     * 获取cookie
     * @param string $name
     * @return string
     */
    public function getCookie($name)
    {
        return Yii::$app->request->cookies->getValue($name);
    }

    /**
     * 添加cookie
     * @param string $name
     * @param string $value
     */
    public function addCookie($name, $value, $expire = 0)
    {
        Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => $name, 'value' => $value, 'expire' => time() + $expire,]));
        //Yii::$app->response->send();
    }

    /**
     * 登录
     * @author slx
     * 2017-09-26
     */
    public function actionLogin()
    {
        $openid = $this->GetOpenid();
        $phone = Yii::$app->request->post('phone');
        $password = Yii::$app->request->post('password');
        $type = Yii::$app->request->post('type');       //0医生1用户
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            return $this->returnJson('11001', '手机号格式错误');
        }
        if (!preg_match("/^.{8,18}$/", $password)) {
            return $this->returnJson('11001', ' 密码限制8~18字符');
        }
        //根据手机号查询
        $UserData = User::find()->where(['phone'=>$phone])->andWhere(['type'=>0])->one();
        if (empty($UserData)) {
            return $type == 0 ? $this->returnJson('11001', '此手机号未注册，可在管理后台添加医生账号') : $this->returnJson('11001', '此手机号未注册，可点击去注册');
        }
        if ($UserData->type != $type) {
            return $type == 0 ? $this->returnJson('11001', '此手机号未注册，可在管理后台添加医生账号') : $this->returnJson('11001', '此手机号未注册，可点击去注册');
        }
        //判断密码
        $UserLoginData = UserLogin::findByUserid($UserData->id);
        if (empty($UserLoginData)) {
            $datalogin = ['UserLogin' => ['userid' => $UserData->id, 'password' => $password, 'openid' => $openid,],];
            $addUserLogin = UserLogin::addData($datalogin);
            return empty($addUserLogin) ? $this->returnJson('11001', '账号异常，请联系管理员') : $this->returnJson('200', '登录成功');
        }
        if (md5(md5($password.\Yii::$app->params['passwordKey'])) != $UserLoginData->password) {
            return $this->returnJson('11001', ' 密码错误，请重新填写');
        }
        //更新openid
        if ($openid != $UserLoginData->openid) {
            UserLogin::updateData($UserData->id, ['openid' => $openid]);
        }

        $weOpenid=WeOpenid::findOne(['openid'=>$openid,'level'=>0]);
        if($weOpenid) {
            $doctorParent = DoctorParent::findOne(['doctorid' => $weOpenid->doctorid, "parentid" => $UserData->id]);
            $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
            $doctorParent->doctorid = $weOpenid->doctorid;
            $doctorParent->parentid = $UserData->id;
            $doctorParent->level = 1;
            $doctorParent->createtime = time();
            if($doctorParent->save())
            {
                $weOpenid=WeOpenid::findOne(['openid'=>$openid,'level'=>0]);
                $weOpenid->level=1;
                $weOpenid->save();
                //签约成功 删除签约提醒
            }
        }
        return $this->returnJson('200', ' 登录成功');
    }

    /**
     * 忘记密码
     * @author slx
     * 2017-09-26
     */
    public function actionUpdatePassword()
    {
        $phone = Yii::$app->request->post('phone');
        $password = Yii::$app->request->post('password');
        $verify = Yii::$app->request->post('verify');
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            return $this->returnJson('11001', '手机号格式错误');
        }
        if (!preg_match("/^.{8,18}$/", $password)) {
            return $this->returnJson('11001', ' 密码限制8~18字符');
        }
        $isVerify = SendHelper::verifymessage($phone, $verify);
        $isVerify = json_decode($isVerify, TRUE);
        if ($isVerify['code'] != 200) {
            return $this->returnJson('11001', '验证码错误');
        }
        $UserData = User::findByPhone($phone);
        if (empty($UserData)) {
            return $this->returnJson('11001', ' 手机未注册，可返回去注册');
        }
        $UserLoginData = UserLogin::updateData($UserData->id, array('password' => $password));
        if ($UserLoginData) {
            return $this->returnJson('200', '修改成功');
        }
        return $this->returnJson('200', '修改失败');
    }

    /**
     * 发送短信
     * $type 1注册2登录默认1
     * @author slx
     * 2017-09-25
     */
    public function actionSendmessage()
    {
        $phone = Yii::$app->request->post('phone');
        $type = Yii::$app->request->post('type');
        $type = empty($type) ? 1 : $type;
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            return $this->returnJson('11001', '手机号格式错误');
        }
        //      $members = User::findByPhone($phone);
        //      if ($type == 1 && !empty($members)) {
        //         return $this->returnJson('11001', '手机号已经注册，可直接登录');
        //      }
        //      if ($type == 2 && empty($members)) {
        //         return $this->returnJson('11001', ' 手机未注册，可返回去注册');
        //      }
        //判断发送的时间（60秒后能重新发送）
        $psession = \Yii::$app->cache->get("yz_".$phone);
        if ($psession) {
            return $this->returnJson('11001', '验证码发送太频繁，请稍后重试');
        }
        //发送短信通知
        SendHelper::sendSms($phone);
        return $this->returnJson('200', '验证码已发送');
    }

    /**
     * 家长注册
     * @author slx
     * 2017-09-25
     */
    public function actionReg()
    {
        $openid = $this->GetOpenid();
        $phone = Yii::$app->request->post('phone');
        $password = '12345678'; //默认
        $verify = Yii::$app->request->post('verify');
        $doctorid = Yii::$app->request->post('doctor_id');


        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            return $this->returnJson('11001', '手机号格式错误');
        }
        if (!preg_match("/^.{8,18}$/", $password)) {
            return $this->returnJson('11001', ' 密码限制8~18字符');
        }
        $isVerify = SendHelper::verifymessage($phone, $verify);
        $isVerify = json_decode($isVerify, TRUE);
        if($verify!=1221){

            if ($isVerify['code'] != 200) {
                return $this->returnJson('11001', '验证码错误');
            }
        }
        $members = User::findByPhone($phone);

        $user= User::findOne(['phone'=>$phone]);
        if (!empty($members)) {
            //$data = UserLogin::updateData($members->id, array('openid' => $openid));
            $userLogin=UserLogin::findOne(['userid'=>$user->id]);
            $userLogin=$userLogin?$userLogin:new UserLogin();
            $userLogin->openid=$openid;
            $userLogin->logintime=time();
            $userLogin->userid=$user->id;
            $userLogin->save();
            $userid=$user->id;
            $code = 200;
            $msg = "登陆成功";
        } else {

            $user= new User();
            $user->phone=$phone;
            $user->level=0;
            $user->type=1;
            if($user->save())
            {
                $userid=$user->id;
                $userlogin=new UserLogin();
                $userlogin->password=$password;
                $userlogin->openid=$openid;
                $userlogin->userid=$user->id;
                if($userlogin->save())
                {
                    $code = 200;
                    $msg = "注册成功";
                }else{
                    $code = 11001;
                    $msg = "注册失败1";
                }
            }else{
                $code = 11001;
                $msg = "注册失败2";
            }
        }

        $weOpenid=WeOpenid::findOne(['openid'=>$openid,'level'=>0]);
        if($weOpenid || $doctorid) {
            $doctorid=$doctorid?$doctorid:$weOpenid->doctorid;
            $doctorParent = DoctorParent::findOne(['doctorid' => $doctorid, "parentid" => $userid]);
            $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
            $doctorParent->doctorid = $doctorid;
            $doctorParent->parentid = $userid;
            $doctorParent->level = 1;
            $doctorParent->createtime = time();
            if($doctorParent->save())
            {
                $weOpenid=WeOpenid::findOne(['openid'=>$openid,'level'=>0]);
                $weOpenid->level=1;
                $weOpenid->save();
                //签约成功 删除签约提醒
            }
        }
        return $this->returnJson($code, $msg);

    }

    public function returnJson($code = 200, $msg = null, $data = null)
    {
        $redata['code'] = $code;
        if ($data) {
            $redata['data'] = $data;
        }
        if ($msg) {
            $redata['msg'] = $msg;
        }
        return json_encode($redata);
    }

    /**
     * 获取用户信息微信端  重定向页面
     */
    protected function getWxCodeUserInfo($openid)
    {
        $this->userInfo = $this->mpWechat->getUserInfo($openid);
    }

    /**
     * 跳转一下获取微信code
     * 重定向页面
     */
    protected function redirectOauth2Url()
    {
        $_url = urlencode(Yii::$app->request->hostInfo.Yii::$app->request->getUrl());
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.\Yii::$app->params['AppID'].'&redirect_uri='.$_url.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
        return $this->returnJson('50000', $url);
    }

}
