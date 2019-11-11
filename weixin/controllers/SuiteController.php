<?php

namespace weixin\controllers;

use EasyWeChat\Factory;
use common\components\HttpRequest;
use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Qrcodeid;
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
        $log = new Log('suite_index');
        $log->addLog("======");
        $log->saveLog();
        $this->mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
        if (isset($_GET['echostr'])) {
            $log->addLog($_GET['echostr']);
            $log->saveLog();
            if ($this->mpWechat->checkSignature()) {
                echo $_GET["echostr"];
                exit;
            }
        } else {

            $postStr = file_get_contents("php://input");
            $log->addLog($postStr);
            $log->saveLog();
            //file_put_contents('/home/wwwlogs/applogs/child.wedoctors.com.cn/wxpost.log',$postStr,FILE_APPEND);
            if (!empty($postStr)) {
                $log->addLog("=====12");
                $log->saveLog();

                $xml = $this->mpWechat->parseRequestXml($postStr, $_GET['msg_signature'], $_GET['timestamp'], $nonce = $_GET['nonce'], $_GET['encrypt_type']);
                $log->addLog("=====32");

                $log->addLog($_GET['timestamp']);
                $log->addLog(implode(',', $xml));
                $log->saveLog();
                //分享是的二维码
                $openid = $xml['FromUserName'];
                if ($xml['Event'] == 'subscribe' || $xml['Event'] == 'SCAN') {


                    $scene = str_replace('qrscene_', '', $xml['EventKey']);
                    if ($scene) {
                        $qrcodeid = Qrcodeid::findOne(['qrcodeid' => $scene, 'type' => 0]);
                        $doctor_id = $qrcodeid->mappingid;
                    } else {
                        $doctor_id = 0;
                    }

                    $weOpenid = WeOpenid::action($xml);
                    if ($doctor_id) {
                        //扫描社区医院二维码操作
                        $userid = UserLogin::findOne(['openid' => $openid])->userid;
                        if ($userid) {
                            $doctorParent = DoctorParent::findOne(['parentid' => $userid]);

                            //已签约 并且签约的医院不是互联网社区医院则 发送提醒
                            if ($doctorParent->level == 1 && $doctorParent->doctorid != 47156) {
                                $doctorName = UserDoctor::findOne(['userid' => $doctorParent->doctorid])->name;

                                $child = ChildInfo::findOne(['userid' => $userid]);
                                $childName = $child->name;

                                $data = [
                                    'first' => array('value' => "﻿您已经签约了" . $doctorName . "\n"),
                                    'keyword1' => ARRAY('value' => $doctorName,),
                                    'keyword2' => ARRAY('value' => $childName ? $childName : "未添加宝宝"),
                                    'keyword3' => ARRAY('value' => date('Y年m月d日', $doctorParent->createtime)),
                                    'keyword4' => ARRAY('value' => "﻿儿童中医药健康指导"),
                                    'remark' => ARRAY('value' => "\n ﻿点击查看详情，如果想变更签约社区请联系工作人员核实信息，感谢！", 'color' => '#221d95'),
                                ];
                                WechatSendTmp::send($data, $openid, "H2rXcOpYlL7oT3ECpyvKaLjMq9QqMMPWuLPle3Y4mbY", "", ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
                                return '';
                            }
                            //$doctorParent = DoctorParent::findOne(['doctorid' => $doctor_id, "parentid" => $userid]);

                            //未签约 或 签约了互联网社区医院（修改签约对象）
                            $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                            $doctorParent->doctorid = $doctor_id;
                            $doctorParent->parentid = $userid;
                            $doctorParent->level = 1;
                            $doctorParent->createtime = time();
                            $doctorParent->save();
                            //已注册用户直接签约成功

                            $user = UserParent::findOne($userid);
                            //微信模板消息
                            $data = ['first' => array('value' => "您有一个新的签约儿童\n"), 'keyword1' => ARRAY('value' => date('Y-m-d H:i')), 'keyword2' => ARRAY('value' => $user->father), 'remark' => ARRAY('value' => "\n点击查看！", 'color' => '#221d95'),];
                            $touser = UserLogin::findOne(['userid' => $doctor_id])->openid;
                            $url = Url::to(\Yii::$app->params['site_url'] . '#/docters-user?p=1');
                            WechatSendTmp::send($data, $touser, \Yii::$app->params['tongzhi'], $url);

                        }


                        //发送签约成功消息
                        $doctor = UserDoctor::findOne($doctor_id);
                        //微信模板消息

                        $data = [
                            'first' => array('value' => "\n预签约已成功，点击完成正式签约"),
                            'keyword1' => ARRAY('value' => $doctor->name,),
                            'keyword2' => ARRAY('value' => $doctor->hospital->name),
                            'keyword3' => ARRAY('value' => date('Y年m月d日')),
                            'remark' => ARRAY('value' => "请务必点击此信息授权进入，如果宝宝信息自动显示，就可以免费享受个性化儿童中医药健康指导服务啦，如果不显示，请正常添加宝宝信息即可", 'color' => '#221d95'),
                        ];
                        $url = \Yii::$app->params['site_url'] . "#/add-docter";
                        WechatSendTmp::send($data, $openid, \Yii::$app->params['chenggong'], $url, ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
                        $this->custom_send($openid);
                        return '';

                    } else {
                        $url = \Yii::$app->params['htmlUrl'] . "#/sign?usertype=parent";
                        $url_doctor = \Yii::$app->params['htmlUrl'] . "#/accountdocter?usertype=docter";

                        $text = "您好，感谢关注儿宝宝！\n\n如果管辖社区卫生服务中心已经开通签约儿保医生服务，请到管辖社区完成扫面签约哦！签约后即可享受中医儿童健康指导，查看健康体检信息及通知，咨询儿保医生等服务\n\n如果社区还没开通此项服务，点击菜单栏 -- 育儿服务 -- 添加宝宝信息,授权成功即可优先免费享有中医儿童健康指导服务";
                        $this->custom_send($openid);
                        return self::sendText($openid, $xml['ToUserName'], $text);
                    }
                } else {
                    //return self::sendText($xml['FromUserName'], $xml['ToUserName'],'...');
                    $this->custom_send($openid);
                    return self::forwardToCustomService($xml['FromUserName'], $xml['ToUserName']);
                }
            } else {
                $log->addLog("end");
                $log->saveLog();
            }
        }
    }

    //客服消息
    public function custom_send($openid){
        sleep(1);
        $config = [
            'app_id' => 'wx1147c2e491dfdf1d',
            'secret' => '98001ba41e010dea2861f3e0d95cbb15',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            //...
        ];

        $app = Factory::officialAccount($config);
        $app->customer_service->message("儿宝宝为您准备了免费的产后恢复课程、亲子游泳体验，请到<a href=\"http://www.qq.com\" data-miniprogram-appid=\"wx6c33bfd66eb0a4f0\" data-miniprogram-path=\"pages/index/index\">儿宝宝福利社</a>中领取")->to($openid)->send();

    }

    //创建菜单
    public function actionCreateMenu()
    {
        $this->mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
        $return=$this->mpWechat->createMenu(
            [
                ['type' => 'miniprogram', 'name' => '育儿服务', 'url' => 'pages/index/index', 'appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',],
                ['type' => 'miniprogram', 'name' => '育儿课堂', 'url' => 'pages/article/index/index', 'appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/article/index/index',],
                //['type' => 'miniprogram', 'name' => '问医生', 'url' => 'pages/index/index', 'appid' => 'wxb7f2ce989de39293', 'pagepath' => 'pages/homePage/homePage',],

//                ['type' => 'view', 'name' => '孕产服务',
//                    'sub_button' => [
//                        [
//                            'type' => 'view',
//                            'name' => '建档攻略',
//                            'url' => 'http://www.xiaomaiyunbao.com/index.php/ien/index/index/tgid/201/',
//                        ],
//                        [
//                            'type' => 'view',
//                            'name' => '建档计算器',
//                            'url' => 'https://www.xiaomaiyunbao.com/index.php/ien/calculator/index/tgid/201',
//                        ],
//                        [
//                            'type' => 'view',
//                            'name' => '建档大礼包',
//                            'url' => 'http://www.xiaomaiyunbao.com/index.php/shop/shop/info/id/4/catid/28/tgid/201?from=singlemessage&isappinstalled=0',
//                        ],
//                        [
//                            'type' => 'view',
//                            'name' => '星级月嫂',
//                            'url' => 'http://www.xiaomaiyunbao.com/index.php/shop/index/ysao_1/tgid/201',
//                        ],
//                        [
//                            'type' => 'view',
//                            'name' => '严选服务',
//                            'url' => 'http://www.xiaomaiyunbao.com/index.php/shop/index/index/tgid/201',
//                        ],
//                    ]
//                ],
                ['type' => 'view', 'name' => '我的', 'sub_button' => [
//                    [
//                        'type' => 'view',
//                        'name' => '订单中心',
//                        'url' => 'http://www.xiaomaiyunbao.com/index.php/shop/user/index/tgid/201',
//                    ],
                    [
                        'type' => 'miniprogram',
                        'name' => '我的预约',
                        'url' => Yii::$app->params['index_url'],
                        'appid' => \Yii::$app->params['wxXAppId'],
                        'pagepath' => 'pages/appoint/my'
                    ],
                    [
                        'type' => 'miniprogram',
                        'name' => '我是家长',
                        'url' => Yii::$app->params['index_url'],
                        'appid' => \Yii::$app->params['wxXAppId'],
                        'pagepath' => 'pages/user/index/index'
                    ],
                    [
                        'type' => 'view',
                        'name' => '我是医生',
                        'url' => 'http://hospital.child.wedoctors.com.cn',
                    ],
                    [
                        'type' => 'miniprogram',
                        'name' => '我的福利',
                        'url' => Yii::$app->params['index_url'],
                        'appid' => 'wx6c33bfd66eb0a4f0',
                        'pagepath' => 'pages/index/index'
                    ],
                ]],

            ]
        );
        var_dump($return);
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
