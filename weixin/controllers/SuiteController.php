<?php

namespace weixin\controllers;

use common\components\HttpRequest;
use common\helpers\WechatSendTmp;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\WeOpenid;
use weixin\models\DoctorParent;
use weixin\models\User;
use weixin\models\UserParent;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use callmez\wechat\sdk\MpWechat;
use yii\web\Cookie;

/**
 * Site controller
 */
class SuiteController extends Controller
{

    public $mpWechat;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $this->mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
        if (isset($_GET['echostr'])) {
            if ($this->mpWechat->checkSignature()) {
                echo $_GET["echostr"];
                exit;
            }
        } else {
            $postStr = file_get_contents("php://input");
            if (!empty($postStr)) {
                $xml = $this->mpWechat->parseRequestXml($postStr, $_GET['msg_signature'], $_GET['timestamp'], $nonce = $_GET['nonce'], $_GET['encrypt_type']);
                $openid = $xml['FromUserName'];
                $doctor_id = str_replace('qrscene_', '', $xml['EventKey']);

                //扫码记录
                $weOpenid=WeOpenid::findOne(['openid'=>$openid,'doctorid'=>$doctor_id]);
                if(!$weOpenid) {
                    $weOpenid = $weOpenid ? $weOpenid : new WeOpenid();
                    $mpWechat = new MpWechat([
                        'token' => \Yii::$app->params['WeToken'],
                        'appId' => \Yii::$app->params['AppID'],
                        'appSecret' => \Yii::$app->params['AppSecret'],
                        'encodingAesKey' => \Yii::$app->params['encodingAesKey']
                    ]);
                    $access_token=$mpWechat->getAccessToken();

                    if($access_token){

                        $path = '/cgi-bin/user/info?access_token='.$access_token."&openid=".$openid."&lang=zh_CN";
                        $curl = new HttpRequest(\Yii::$app->params['wxUrl'].$path, true, 2);
                        $userJson = $curl->get();
                        $userInfo=json_decode($userJson,true);
                        if($userInfo['unionid']) {
                            $weOpenid->unionid=$userInfo['unionid'];
                        }
                    }
                    $weOpenid->openid = $openid;
                    $weOpenid->doctorid = $doctor_id;
                    $weOpenid->createtime = time();
                    $weOpenid->save();
                }
                //分享是的二维码
                if ($xml['Event'] == 'subscribe' || $xml['Event'] == 'SCAN') {

                    $userid = UserLogin::findOne(['openid' => $openid])->userid;
                    if ($userid && $doctor_id) {
                        //已注册用户签约流程
                        $doctorParent=DoctorParent::findOne(['parentid' => $userid]);
                        if ($doctorParent) {
                            $doctorName=UserDoctor::findOne(['userid'=>$doctorParent->doctorid])->name;
                            //发送提醒已签约其他医生
                            $url = \Yii::$app->params['htmlUrl']."#/first-login-adviser?doctor_id=".$doctorParent->doctorid;

                            return self::sendText($openid, $xml['ToUserName'], "您已经签约了【{$doctorName}】\n\n <a href='{$url}'>点击查看团队</a>");
                        } elseif ($doctor_id) {
                            //发送提醒"已提交申请，等待医生审核"
                            //给医生发送提醒消息
                            //不需要审核 直接审核通过
                            $doctorParent = DoctorParent::findOne(['doctorid' => $doctor_id, "parentid" => $userid]);
                            $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                            $doctorParent->doctorid = $doctor_id;
                            $doctorParent->parentid = $userid;
                            $doctorParent->level = 1;
                            if ($doctorParent->save()) {
                                $user = UserParent::findOne($userid);
                                //微信模板消息
                                $data = ['first' => array('value' => "您有一个新的签约儿童\n"), 'keyword1' => ARRAY('value' => date('Y-m-d H:i')), 'keyword2' => ARRAY('value' => $user->father), 'remark' => ARRAY('value' => "\n点击查看！", 'color' => '#221d95'),];
                                $touser = UserLogin::findOne(['userid' => $doctor_id])->openid;
                                $url = Url::to(\Yii::$app->params['site_url'].'#/docters-user?p=1');
                                WechatSendTmp::send($data, $touser, \Yii::$app->params['tongzhi'], $url);

                                //发送签约成功消息
                                $doctor = UserDoctor::findOne($doctor_id);
                                //微信模板消息

                                $data = [
                                    'first' => array('value' => "恭喜您，已成功签约。\n"),
                                    'keyword1' => ARRAY('value' => $doctor->name, ),
                                    'keyword2' => ARRAY('value' => $doctor->hospital->name),
                                    'keyword3' => ARRAY('value' => date('Y年m月d日')),
                                    'remark' => ARRAY('value' => "\n 点击授权并添加宝宝信息，可以免费享受个性化中医儿童健康指导等服务。", 'color' => '#221d95'),
                                ];
                                $url = \Yii::$app->params['site_url']."#/add-docter";
                                WechatSendTmp::send($data, $openid, \Yii::$app->params['chenggong'], $url,['appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/index/index',]);
                                return '';
                                //return self::sendText($openid, $xml['ToUserName'], "您已成功签约".UserDoctor::findOne($doctor_id)->name);
                            } else {
                                return self::sendText($openid, $xml['ToUserName'], "失败：".json_encode($doctorParent->firstErrors));

                            }
                        }
                    } elseif ($doctor_id) {
                        //未注册用户签约流程
                        $url = Yii::$app->params['index_url'];
                        //发送签约成功消息
                        $doctor = UserDoctor::findOne($doctor_id);
                        //微信模板消息

                        $data = [
                            'first' => array('value' => "恭喜您，已成功签约。\n"),
                            'keyword1' => ARRAY('value' => $doctor->name, ),
                            'keyword2' => ARRAY('value' => $doctor->hospital->name),
                            'keyword3' => ARRAY('value' => date('Y年m月d日')),
                            'remark' => ARRAY('value' => "\n 点击授权并添加宝宝信息，可以免费享受个性化中医儿童健康指导等服务。", 'color' => '#221d95'),
                        ];
                        $url = \Yii::$app->params['site_url']."#/add-docter";
                        WechatSendTmp::send($data, $openid, \Yii::$app->params['chenggong'], $url,['appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/index/index',]);
                        return '';
                        //return self::sendText($openid, $xml['ToUserName'], UserDoctor::findOne($doctor_id)->name."：恭喜你签约成功，针对不同月龄儿童，可享受以下服务：\n1.解答儿童日常健康问题\n2.个性化中医儿童健康指导\n3.记录和查看儿童健康档案\n4.体检及疫苗服务的温馨提醒\n\n <a href='{$url}'>点击查看预防保健团队，并完善信息</a>");
                    } else {
                        $url = \Yii::$app->params['htmlUrl']."#/sign?usertype=parent";
                        $url_doctor = \Yii::$app->params['htmlUrl']."#/accountdocter?usertype=docter";

                        $text = "如果您是儿童家长：\n1.微信扫医生提供二维码，可以添加儿保医生。\n2.注册完善信息，将可以查看家长课堂，包括儿童饮食、运动、中医按摩等视频文章。\n<a href='{$url}'>点击去注册</a>\n如果您是医生：\n<a href='{$url_doctor}'>点击去登录医生账号</a>";
                        return self::sendText($openid, $xml['ToUserName'], $text);
                    }
                } else {
                    return self::forwardToCustomService($xml['FromUserName'], $xml['ToUserName']);
                }
            }
        }
    }

    //创建菜单
    public function actionCreateMenu()
    {
        $this->mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
        $this->mpWechat->createMenu(
            [
                ['type' => 'miniprogram', 'name' => '育儿服务', 'url' =>'pages/index/index','appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/index/index',],
                ['type' => 'miniprogram', 'name' => '育儿课堂', 'url' => 'pages/article/index/index','appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/article/index/index',],
                ['type' => 'view', 'name' => '中医健康管理', 'url' => Yii::$app->params['index_url'],],
            ]
        );
    }

    public static function sendText($openid, $tousername, $content)
    {
        $template = <<<XML
    <xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
    </xml>
XML;
        return sprintf($template, $openid, $tousername, time(), $content);
    }

    /**
     * 将消息转发到多客服
     * 如果公众号处于开发模式，需要在接收到用户发送的消息时，返回一个MsgType为transfer_customer_service的消息，微信服务器在收到这条消息时，会把这次发送的消息转到多客服系统。用户被客服接入以后，客服关闭会话以前，处于会话过程中，用户发送的消息均会被直接转发至客服系统。
     * @param $fromusername
     * @param $tousername
     * @return string
     */
    public static function forwardToCustomService($fromusername, $tousername)
    {
        $template = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>
XML;
        return sprintf($template, $fromusername, $tousername, time());
    }

}
