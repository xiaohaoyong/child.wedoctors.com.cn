<?php

namespace weixin\controllers;

use common\models\Appoint;
use common\models\AppointCalling;
use common\models\AppointCallingList;
use common\models\HospitalAppoint;
use common\models\queuing\Queue;
use EasyWeChat\Factory;
use common\components\HttpRequest;
use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Qrcodeid;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\WeOpenid;
use EasyWeChat\Kernel\Messages\Message;
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
//        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
//        $app->server->push(function ($message) {
//
//        });
//        $response = $app->server->serve();
//        $response->send();

        $log = new Log('suite_index');
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
            if (!empty($postStr)) {
                $xml = $this->mpWechat->parseRequestXml($postStr, $_GET['msg_signature'], $_GET['timestamp'], $nonce = $_GET['nonce'], $_GET['encrypt_type']);
                $log->addLog($_GET['timestamp']);
                $log->addLog(implode(',', $xml));
                $log->saveLog();

                // $loga = new Log('subscribe');
                //分享是的二维码
                $openid = $xml['FromUserName'];
                if ($xml['Event'] == 'subscribe' || $xml['Event'] == 'SCAN') {
//                    $loga->addLog(file_get_contents('php://input'));
//                    $loga->addLog($_GET['msg_signature'] . '|||' . $_GET['timestamp'] . '|||' . $nonce = $_GET['nonce'] . '|||' . $_GET['encrypt_type']);
//                    $loga->saveLog();

                    $scene = str_replace('qrscene_', '', $xml['EventKey']);

                    if ($scene) {
                        $qrcodeid = Qrcodeid::findOne(['qrcodeid' => $scene]);
                        if ($qrcodeid->type == 0) {
                            $doctor_id = $qrcodeid->mappingid;
                        } elseif ($qrcodeid->type == 1) {
                            //线上叫号系统
                            $user = UserLogin::findOne(['openid' => $openid]);
                            $appoint = Appoint::find()->where(['doctorid' => $qrcodeid->mappingid, 'userid' => $user->userid, 'appoint_date' => strtotime('today')])
                                ->andWhere(['!=', 'state', 3])->one();
                            if (!$appoint) {
                                return self::sendText($xml['FromUserName'], $xml['ToUserName'], "未查询到您的预约信息，请点击链接输入您的预约码：:http://web.child.wedoctors.com.cn/wappoint");
                            } else {
                                $appointCallingListModel = AppointCallingList::findOne(['aid' => $appoint->id]);
                                //判断用户是否已经排队
                                if ($appointCallingListModel) {
                                    if ($appointCallingListModel->state == 1) {
                                        $queue = new Queue($qrcodeid->mappingid, $appoint->type, $appoint->appoint_time);
                                        $num2 = $queue->search($appointCallingListModel->id);
                                        $text[] = "日期：" . date('Y年m月d日 H:i');
                                        $text[] = "号码：" . AppointCallingList::listName($appointCallingListModel->id, $qrcodeid->mappingid, $appoint->type);
                                        $text[] = "前方等待：" . $num2 . "位";
                                        $text[] = "叫号会通过微信公众号发送，请时刻关注，以免错过!";
                                        $txt = implode("\n", $text);
                                        return self::sendText($xml['FromUserName'], $xml['ToUserName'], $txt);
                                    } elseif ($appointCallingListModel->state == 3) {
                                        return self::sendText($xml['FromUserName'], $xml['ToUserName'], '您的预约已完成');
                                    } elseif ($appointCallingListModel->state == 2) {


                                        //过期重排
                                        $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $qrcodeid->mappingid, 'type' => $appoint->type]);
                                        $timeType = Appoint::getTimeType($hospitalAppoint->interval, date('H:i'));
                                        $appointCallingListModel->time = $timeType;
                                        if ($appointCallingListModel->save()) {
                                            $queue = new Queue($qrcodeid->mappingid, $appoint->type, $appoint->appoint_time);
                                            $queueNum = $queue->lpush($appointCallingListModel->id);
                                            $text[] = "您的预约已过期，系统已为您重新排号：";
                                            $text[] = "日期：" . date('Y年m月d日 H:i');
                                            $text[] = "号码：" . AppointCallingList::listName($appointCallingListModel->id, $qrcodeid->mappingid, $appoint->type);
                                            $text[] = "前方等待：" . ($queueNum - 1) . "位";
                                            $txt = implode("\n", $text);
                                            return self::sendText($xml['FromUserName'], $xml['ToUserName'], $txt . "\n 叫号会通过微信公众号发送，请时刻关注，以免错过叫号!");
                                        }

                                    }
                                } else {
                                    //排队
                                    $appointCallingListModel = new AppointCallingList();
                                    $appointCallingListModel->aid = $appoint->id;
                                    $appointCallingListModel->openid = $openid;
                                    $appointCallingListModel->doctorid = $qrcodeid->mappingid;
                                    $appointCallingListModel->time = $appoint->appoint_time;
                                    $appointCallingListModel->type = $appoint->type;
                                    if ($appointCallingListModel->save()) {
                                        $queue = new Queue($qrcodeid->mappingid, $appoint->type, $appoint->appoint_time);
                                        $queueNum = $queue->lpush($appointCallingListModel->id);

                                        $text[] = "日期：" . date('Y年m月d日 H:i');
                                        $text[] = "号码：" . AppointCallingList::listName($appointCallingListModel->id, $qrcodeid->mappingid, $appoint->type);
                                        $text[] = "前方等待：" . ($queueNum - 1) . "位";
                                        $txt = implode("\n", $text);
                                        return self::sendText($xml['FromUserName'], $xml['ToUserName'], $txt . "\n 叫号会通过微信公众号发送，请时刻关注，以免错过叫号!");
                                    }
                                }
                            }
                        }
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
                                    'remark' => ARRAY('value' => "\n ﻿点击查看详情，如果想变更签约社区请联系小助手核实信息，感谢！小助手微信号（erbbzs）", 'color' => '#221d95'),
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

//                            $user = UserParent::findOne($userid);
//                            //微信模板消息
//                            $data = ['first' => array('value' => "您有一个新的签约儿童\n"), 'keyword1' => ARRAY('value' => date('Y-m-d H:i')), 'keyword2' => ARRAY('value' => $user->father), 'remark' => ARRAY('value' => "\n点击查看！", 'color' => '#221d95'),];
//                            $touser = UserLogin::findOne(['userid' => $doctor_id])->openid;
//                            $url = Url::to(\Yii::$app->params['site_url'] . '#/docters-user?p=1');
//                            WechatSendTmp::send($data, $touser, \Yii::$app->params['tongzhi'], $url);

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

                        if ($doctor->county == 1114) {
                            return self::sendText($xml['FromUserName'], $xml['ToUserName'], '昌平区用户您好，如果您有其他服务需求，推荐您下载使用昌平健康云APP');
                        }
                        return '';
                    } else {
                        $url = \Yii::$app->params['htmlUrl'] . "#/sign?usertype=parent";
                        $url_doctor = \Yii::$app->params['htmlUrl'] . "#/accountdocter?usertype=docter";

                        $text = "您好，感谢关注儿宝宝！\n\n如果管辖社区卫生服务中心已经开通签约儿保医生服务，回复SQ+社区名称即可获取社区二维码（如：“SQ小红门”，）长按并识别二维码即可签约成功并享受中医儿童健康指导，查看健康体检信息及通知，咨询儿保医生等服务\n\n如需要查询社区名称请访问：http://child.wedoctors.com.cn/doctors 查询社区后长按并识别二维码即可\n\n如果社区还没开通此项服务，点击菜单栏 -- 育儿服务 -- 添加宝宝信息,授权成功即可优先免费享有中医儿童健康指导服务";
                        $return = self::sendText($openid, $xml['ToUserName'], $text);
                        $this->custom_send($openid);
                        return $return;
                    }
                } else {


                    if ($xml['Event'] == 'TEMPLATESENDJOBFINISH') {

                        return '';

                    }

                    $str = strtolower(mb_substr($xml['Content'], 0, 2, 'utf-8'));
                    switch ($str) {
                        case 'sq':
                            $docName = substr($xml['Content'], 2);
                            if ($docName) {
                                $query = UserDoctor::find();
                                if ($docName) {
                                    $query->andFilterWhere(['like', 'name', $docName]);
                                }
                                $doctors = $query->orderBy('appoint desc')->all();
                                $docName = urlencode($docName);

                                if (count($doctors) > 1) {
                                    return self::sendText($xml['FromUserName'], $xml['ToUserName'], "查询到多个结果请访问链接查看:http://child.wedoctors.com.cn/doctors?search={$docName}");
                                } else {
                                    return self::sendText($xml['FromUserName'], $xml['ToUserName'], "请访问：http://child.wedoctors.com.cn/doctors?search={$docName} 查询结果");

                                }
                            }
                            break;
                    }
                    if ($xml['Content'] == '成人疫苗') {
                        return self::sendText($xml['FromUserName'], $xml['ToUserName'], "预约成人疫苗请点击下方链接，选择社区后预约:http://web.child.wedoctors.com.cn/wappoint");

                    }


                    return self::sendText($xml['FromUserName'], $xml['ToUserName'], '宝宝家长，您在社区医院遇到体检查看问题，疫苗预约、体检预约、健康指导等问题时，可以添加儿宝小助手客服帮您进行解答。客服微信号（erbbzs）工作日内可随时联系儿宝小助手，我们会第一时间回复您的问题。');
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
    public function custom_send($openid)
    {
        return;
        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $app->customer_service->message("儿宝宝为您准备了免费的产后恢复课程、亲子游泳体验，请到<a href=\"http://www.qq.com\" data-miniprogram-appid=\"wx6c33bfd66eb0a4f0\" data-miniprogram-path=\"pages/index/index\">儿宝宝福利社</a>中领取")->to($openid)->send();

    }

    //创建菜单
    public function actionCreateMenu()
    {
        $buttons = [
            ['type' => 'miniprogram', 'name' => '育儿服务', 'url' => 'pages/index/index', 'appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',],
            ['type' => 'view', 'name' => '问医生', 'url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1147c2e491dfdf1d&redirect_uri=http://web.child.wedoctors.com.cn/haodf&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect',],
            ['type' => 'view', 'name' => '我的', 'sub_button' => [
                [
                    'type' => 'view',
                    'name' => '流行病学调查表',
                    'url' => 'http://web.child.wedoctors.com.cn/question-naire/form?id=1',
                ],
                [
                    'type' => 'view',
                    'name' => '预约成人疫苗/两癌筛查',
                    'url' => 'http://web.child.wedoctors.com.cn/qappoint/list',
                ],

                [
                    'type' => 'miniprogram',
                    'name' => '我的宝宝预约',
                    'url' => Yii::$app->params['index_url'],
                    'appid' => \Yii::$app->params['wxXAppId'],
                    'pagepath' => 'pages/appoint/my'
                ],
//                    [
//                        'type' => 'miniprogram',
//                        'name' => '我是家长',
//                        'url' => Yii::$app->params['index_url'],
//                        'appid' => \Yii::$app->params['wxXAppId'],
//                        'pagepath' => 'pages/user/index/index'
//                    ],
                [
                    'type' => 'miniprogram',
                    'name' => '我是医生',
                    'url' => "http://hospital.child.wedoctors.com.cn",
                    'appid' => "wx6835027c46b29cab",
                    'pagepath' => 'pages/index/index'
                ],
                [
                    'type' => 'miniprogram',
                    'name' => '我的福利',
                    'url' => Yii::$app->params['index_url'],
                    'appid' => 'wx6c33bfd66eb0a4f0',
                    'pagepath' => 'pages/index/index'
                ],
            ]],

        ];
        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $a = $app->menu->create($buttons);
        var_dump($a);
        exit;
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
