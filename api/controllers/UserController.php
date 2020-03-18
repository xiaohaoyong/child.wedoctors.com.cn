<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace api\controllers;

use api\controllers\Controller;

use common\components\Log;
use common\helpers\HuanxinHelper;
use common\helpers\HuanxinUserHelper;
use common\components\Code;
use common\components\HttpRequest;
use common\components\wx\WxBizDataCrypt;
use common\helpers\SendHelper;
use common\helpers\SmsSend;
use common\models\ArticleSend;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Notice;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;
use common\models\WxInfo;
use yii\web\UploadedFile;

class UserController extends Controller
{

    public function actionMessage()
    {
        $value = (string)time();
        return HuanxinHelper::setTxtMessage('wangzhentest', '72e2caa55d4934c1a74255550af7b76e', $value);
    }

    /**
     * 登录
     * @param $code
     * @return array
     */
    public function actionLogin($code)
    {
        $log=new Log("login");
        //已登录
        if ($this->userLogin && $this->seaver_token) {
            $log->addLog("已登录");
            $log->addLog("userlogin:".$this->userLogin->userid);
            $log->addLog("seaver_token:".$this->seaver_token);


            $session_key = $this->seaver_token;
            $login = UserLogin::findOne(['id'=>$this->userLogin->id]);
            $xopenid = $login->xopenid;
            $unionid = $login->unionid;
            $useridKey = md5($this->userid . "6623cXvY");

            $log->addLog("xopenid:".$login->xopenid);
            $log->addLog("unionid:".$login->unionid);
        } else {
            $log->addLog("未登录");

            //获取用户微信登陆信息
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['wxXAppId'] . "&secret=" . \Yii::$app->params['wxXAppSecret'] . "&js_code=" . $code . "&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
            $userJson = $curl->get();
            $log->addLog("wxrequist:".$userJson);
            $user = json_decode($userJson, true);
            //$log->addLog($user);
            if ($user['errcode'] || !$user) {
                $log->addLog("error:".$curl->getMsg());

                //获取用户微信登陆信息
                $log->addLog("false1");
                $path = "/sns/jscode2session?appid=" . \Yii::$app->params['wxXAppId'] . "&secret=" . \Yii::$app->params['wxXAppSecret'] . "&js_code=" . $code . "&grant_type=authorization_code";
                $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
                $userJson = $curl->get();
                $log->addLog("wxrequist:".$userJson);
                $user = json_decode($userJson, true);

                if ($user['errcode'] || !$user) {
                    $log->addLog("false2");
                    $log->saveLog();
                    return new Code(30001, $user['errmsg']);
                }
            }
            $value = $user['openid'] . '@@' . $user['session_key'] . '@@' . $user['unionid'];
            $log->addLog("value:".$value);
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
                    $log->addLog("openid:".$weOpenid->openid);
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
                    $log->addLog("userlogin:".$userLogin->id);
                    if ($weOpenid->openid) {
                        $userLogin->openid = $weOpenid->openid;
                    }
                    $userLogin->xopenid = $user['openid'];
                    $userLogin->unionid = $user['unionid'];
                    $userLogin->save();
                    $log->addLog("登录:".json_encode($userLogin->firstErrors));

                }


            }

            $xopenid = $user['openid'];
            $useridKey = $userid ? md5($userid . "6623cXvY") : 0;


        }

        $huanxin = md5($xopenid . '7Z9WL3s2');
        //HuanxinUserHelper::getUserInfo($huanxin);
        $log->addLog("环信:".$huanxin);

        if($xopenid) {
            //对第一次登陆用户发送欢迎消息
            $cache = \Yii::$app->rdmp;
            $firstLogin = $cache->hget('firstLogin', $xopenid);
            if (!$firstLogin) {
                //HuanxinHelper::setTxtMessage('wangzhentest',$huanxin,'欢迎使用中医儿童健康管理工具');
            }
            $cache->hset('firstLogin', $xopenid, time());
        }
        $log->saveLog();

        return ['sessionKey' => $session_key, 'userKey' => $useridKey, 'userName' => $huanxin];

    }

    /**
     * 授权
     * @return array
     */
    public function actionWxUserInfo()
    {
        $log=new Log("weOpenidlevel");

        if ($this->userLogin) {

            $type = 0;
            $useridx = md5($this->userLogin->userid . "6623cXvY");
            $userLogin = UserLogin::findOne(['id'=>$this->userLogin->id]);
            $userLogin->logintime = time();
            $userLogin->save();
            $doctorParent = DoctorParent::findOne(['parentid' => $userLogin->userid]);

            $log->addLog($this->userLogin->userid."已登录");
        } else {

            $appid = \Yii::$app->params['wxXAppId'];
            $cache = \Yii::$app->rdmp;
            $session = $cache->get($this->seaver_token);
            $session = explode('@@', $session);
            $openid = $session[0];
            $unionid = $session[2];
            $log->addLog('seaver_token:'.$this->seaver_token);

            $log->addLog("openid:".$openid);
            $log->addLog("unionid:".$unionid);
            $log->addLog("session_key:".$session[1]);

            $login = UserLogin::find();
            $login->andwhere(['type'=>0]);

            if($openid!=='' and $unionid!=''){
                $login->andWhere(['or',['xopenid' => $openid],['unionid' => $unionid]]);
            }elseif ($openid != '') {
                $login->andWhere(['xopenid' => $openid]);
            }elseif ($unionid != '') {
                $login->andWhere(['unionid' => $unionid]);
            }
            if ($openid || $unionid) {
                $userLogin = $login->one();
            }

            if ($userLogin) {
                $type = 1;
                $useridx = md5($userLogin->userid . "6623cXvY");
                $userLogin->logintime = time();
                $userLogin->save();
                $doctorParent = DoctorParent::findOne(['parentid' => $userLogin->userid]);
                $log->addLog("已登录过");
            } else {
                $log->addLog("未登录过");

                //获取用户手机号
                $phoneEncryptedData = \Yii::$app->request->post('phoneEncryptedData');
                $phoneIv = \Yii::$app->request->post('phoneIv');
                $pc = new WxBizDataCrypt($appid, $session[1]);
                $code = $pc->decryptData($phoneEncryptedData, $phoneIv, $phoneJson);
                $phone = json_decode($phoneJson, true);
                if($phone['phoneNumber']){
                    $log->addLog("phone:" . $phone['phoneNumber']);

                }else {

                    $log->addLog("session:" . $session[1]);
                    $log->addLog("phone:" . $phoneJson);
                    $log->addLog("code:" . $code);
                    $log->addLog("phoneEncryptedData:" . $phoneEncryptedData);
                    $log->addLog("phoneIv:" . $phoneIv);

                }

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
                        $userid=$userLogin->userid;
                        $log->addLog("已录入登录手机号");

                    } else {
                        $log->addLog("未录入登录手机号");

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
                    $log->addLog("userid:".$userid);



                    //Notice::setList($userid, 6, ['title' => '身高预测', 'ftitle' => '健康工具', 'id' => '/tool/height/index',]);
                    Notice::setList($userid, 3, ['title' => '儿童中医药健康管理内容及平台服务', 'ftitle' => '点击查看服务内容', 'id' => '/article/view/index?id=200',]);

                    $userLogin = UserLogin::findOne(['userid' => $userid, 'phone' => $wephone]);
                    $userLogin = $userLogin ? $userLogin : new UserLogin();

                    $childInfo = ChildInfo::find()->andFilterWhere(['userid' => $userid])->andFilterWhere(['>', 'source', 38])->orderBy('birthday desc')->one();

                    if($childInfo) {
                        $log->addLog("childid:".$childInfo->id);
                        $doctor = UserDoctor::findOne(['hospitalid' => $childInfo->source]);
                        $default = $doctor ? $doctor->userid : 47156;
                        $doctorid = $default;
                    }else{
                        $doctorid=47156;
                        $default=47156;
                    }
//扫码签约
                    $log->addLog("doctorid:".$doctorid);

                    $weOpenid = WeOpenid::findOne(['unionid' => $unionid]);
                    if ($weOpenid) {
                        $doctorid = $weOpenid->doctorid ? $weOpenid->doctorid : $default;
                        $userLogin->openid = $weOpenid->openid;
                        $log->addLog("weOpenid:".$weOpenid->id);
                    }

                    $doctorParent = DoctorParent::findOne(['parentid' => $userid]);

                    if (!$doctorParent || $doctorParent->level != 1) {
                        $log->addLog("未签约");
                        $isdoctorP=1;
                        $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                        $doctorParent->doctorid = $doctorid;
                        $doctorParent->parentid = $userid;
                        $doctorParent->level = 1;
                        $doctorParent->createtime = time();
                        if ($doctorParent->save()) {
                            $log->addLog("签约:".implode(',',$doctorParent->firstErrors));
                            $userDoctor = UserDoctor::findOne(['userid' => $doctorid]);
                            if ($userDoctor) {
                                $hospital = $userDoctor->hospitalid;
                            }
                            ChildInfo::updateAll(['doctorid' => $hospital], 'userid=' . $userid);
                            $log->addLog('doctorid:'.$hospital);
                            //签约成功 删除签约提醒
                        }
                    }
                    if($doctorParent && $doctorParent->level==1 && $weOpenid){
                        $weOpenid->level = 1;
                        $weOpenid->save();
                        $log->addLog("扫码状态:".implode(',',$weOpenid->firstErrors));
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
                    $log->addLog("更新登录:".implode(',',$userLogin->firstErrors));


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
            }

        }
        $log->addLog('useridx'.$useridx);

        $log->saveLog();

        if($childInfo) {
            if ($doctorParent) {
                $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
            }
            $autograph = Autograph::findOne(['userid' => $this->userid]);
        }
        return ['useridx' => $useridx, 'type' => $type,'doctor'=>$doctor,'is_autograph'=>$autograph?0:1];
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

    /**
     * 发送验证码
     * @param $phone
     * @return Code
     */
    public function actionCode($phone){
        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
            return new Code(20010,'手机号码格式错误！');
        }
        $sendData=SmsSend::sendSms($phone,'SMS_150575871');
    }

    /**
     * 验证验证码
     * @param $phone
     * @param $code
     * @return Code
     */
    public function actionGetCode($phone,$code){
        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
            return new Code(20010,'手机号码验证失败');
        }

        $isVerify = SmsSend::verifymessage($phone, $code);
        $isVerify = json_decode($isVerify, TRUE);
        if ($isVerify['code'] != 200) {
            return new Code(20010,'手机验证码错误');
        }
    }

    /**
     * 保存签名图片
     * @return Code
     */
    public function actionSaveImage(){
        $autograph=Autograph::findOne(['loginid'=>$this->userLogin->id,'userid'=>$this->userid]);

        $imagesFile = UploadedFile::getInstancesByName('file');
        $img=$imagesFile[0]->tempName;

        $rotang = 90; // Rotation angle
        $source = imagecreatefrompng($img) or die('Error opening file '.$img);
        imagealphablending($source, false);
        imagesavealpha($source, true);

        $rotation = imagerotate($source, $rotang, imageColorAllocateAlpha($source, 0, 0, 0, 127));
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);
        imagepng($rotation,$img);


        if($imagesFile) {
            $upload= new \common\components\UploadForm();
            $upload->imageFiles = $imagesFile;
            $image = $upload->upload();
            $data['img']=$image[0];
            $data['loginid']=$this->userLogin->id;
            $data['userid']=$this->userid;
            $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
            $data['doctorid']=$doctorParent->doctorid;


            $auto=Autograph::find()->where(['userid'=>$this->userid])->one();
            if(!$auto) {
                $autograph = $autograph ? $autograph : new Autograph();
                $autograph->load(['Autograph' => $data]);
                $autograph->save();
                if ($autograph->firstErrors) {
                    return new Code(20010, $autograph->firstErrors);
                }
            }
        }
        return $image[0];
    }
}