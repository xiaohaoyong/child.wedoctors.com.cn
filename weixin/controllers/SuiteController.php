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
            //file_put_contents('/home/wwwlogs/applogs/child.wedoctors.com.cn/wxpost.log',$postStr,FILE_APPEND);
            if (!empty($postStr)) {
                $xml = $this->mpWechat->parseRequestXml($postStr, $_GET['msg_signature'], $_GET['timestamp'], $nonce = $_GET['nonce'], $_GET['encrypt_type']);

                //分享是的二维码
                if ($xml['Event'] == 'subscribe' || $xml['Event'] == 'SCAN') {
                    $openid = $xml['FromUserName'];
                    $doctor_id = str_replace('qrscene_', '', $xml['EventKey']);
                    $doctor_id=$doctor_id?$doctor_id:0;
                    $weOpenid=WeOpenid::action($xml);

                    if($doctor_id)
                    {
                        //扫描社区医院二维码操作
                        $userid = UserLogin::findOne(['openid' => $openid])->userid;
                        if($userid)
                        {
                            $doctorParent=DoctorParent::findOne(['parentid' => $userid]);

                            //已签约 并且签约的医院不是互联网社区医院则 发送提醒
                            if ($doctorParent->level==1 && $doctorParent->doctorid!=47156) {
                                $doctorName=UserDoctor::findOne(['userid'=>$doctorParent->doctorid])->name;
                                //发送提醒已签约其他医生
                                $url = \Yii::$app->params['htmlUrl']."#/first-login-adviser?doctor_id=".$doctorParent->doctorid;
                                return self::sendText($openid, $xml['ToUserName'], "您已经签约了【{$doctorName}】\n\n <a href='{$url}'>点击查看团队</a>");
                            }
                            //$doctorParent = DoctorParent::findOne(['doctorid' => $doctor_id, "parentid" => $userid]);

                            //未签约 或 签约了互联网社区医院（修改签约对象）
                            $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                            $doctorParent->doctorid = $doctor_id;
                            $doctorParent->parentid = $userid;
                            $doctorParent->level = 1;
                            $doctorParent->createtime=time();
                            $doctorParent->save();
                            //已注册用户直接签约成功

                            $user = UserParent::findOne($userid);
                            //微信模板消息
                            $data = ['first' => array('value' => "您有一个新的签约儿童\n"), 'keyword1' => ARRAY('value' => date('Y-m-d H:i')), 'keyword2' => ARRAY('value' => $user->father), 'remark' => ARRAY('value' => "\n点击查看！", 'color' => '#221d95'),];
                            $touser = UserLogin::findOne(['userid' => $doctor_id])->openid;
                            $url = Url::to(\Yii::$app->params['site_url'].'#/docters-user?p=1');
                            WechatSendTmp::send($data, $touser, \Yii::$app->params['tongzhi'], $url);

                        }


                        //发送签约成功消息
                        $doctor = UserDoctor::findOne($doctor_id);
                        //微信模板消息

                        $data = [
                            'first' => array('value' => "恭喜您，已成功签约。\n"),
                            'keyword1' => ARRAY('value' => $doctor->name, ),
                            'keyword2' => ARRAY('value' => $doctor->hospital->name),
                            'keyword3' => ARRAY('value' => date('Y年m月d日')),
                            'remark' => ARRAY('value' => "\n 点击添加宝宝并授权，就可以免费享受个性化中医儿童健康指导等服务，如果授权的手机号码与建档时不一致，则宝宝的信息不会显示，正常添加宝宝信息即可（需准确），不影响功能使用", 'color' => '#221d95'),
                        ];
                        $url = \Yii::$app->params['site_url']."#/add-docter";
                        WechatSendTmp::send($data, $openid, \Yii::$app->params['chenggong'], $url,['appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/index/index',]);
                        return '';

                    }
                    else {
                        $url = \Yii::$app->params['htmlUrl']."#/sign?usertype=parent";
                        $url_doctor = \Yii::$app->params['htmlUrl']."#/accountdocter?usertype=docter";

                        $text = "您好，感谢关注儿宝宝！\n\n如果管辖社区卫生服务中心已经开通签约儿保医生服务，请到管辖社区完成扫面签约哦！签约后即可享受中医儿童健康指导，查看健康体检信息及通知，咨询儿保医生等服务\n\n如果社区还没开通此项服务，点击菜单栏 -- 育儿服务 -- 添加宝宝信息,授权成功即可优先免费享有中医儿童健康指导服务";
                        return self::sendText($openid, $xml['ToUserName'], $text);
                    }
                }
                else {
                    //return self::sendText($xml['FromUserName'], $xml['ToUserName'],'...');

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
                ['type' => 'view', 'name' => '我的', 'sub_button' =>[
                    [
                        'type'=>'miniprogram',
                        'name'=>'我是家长',
                        'url'=>Yii::$app->params['index_url'],
                        'appid'=>\Yii::$app->params['wxXAppId'],
                        'pagepath'=>'pages/user/index/index'
                    ],
                    [
                        'type'=>'view',
                        'name'=>'我是医生',
                        'url'=>Yii::$app->params['index_url'],
                    ]
                ]],

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
        return sprintf($template,$fromusername,$tousername, time());
    }

}
