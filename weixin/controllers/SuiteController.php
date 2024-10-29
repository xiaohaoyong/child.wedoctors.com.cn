<?php

namespace weixin\controllers;

use common\models\Appoint;
use common\models\AppointCalling;
use common\models\AppointCallingList;
use common\models\HospitalAppoint;
use common\models\MpEventPush;
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
        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
//        $accessToken = $app->access_token;
//        $token = $accessToken->getToken(true);
//        $app['access_token']->setToken($token['access_token'], 7200);
        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    if($message['Event'] == 'subscribe' || $message['Event'] == 'SCAN'){
                        $openid = $message['FromUserName'];
                        $scene = str_replace('qrscene_', '', $message['EventKey']);
                        $qrcodeid = Qrcodeid::findOne(['qrcodeid' => $scene]);
                        $doctor_id = $qrcodeid->mappingid;
                        try {
                            $weOpenid = WeOpenid::action($message);
                        }catch (\Exception $e){
                            return  $e->getMessage();
                        }
                        if ($doctor_id) {
                            //扫描社区医院二维码操作
                            $userid = UserLogin::findOne(['openid' => $openid])->userid;
                            if ($userid) {
                                $doctorParent = DoctorParent::findOne(['parentid' => $userid]);

                                //已签约 并且签约的医院不是互联网社区医院则 发送提醒
                                if ($doctorParent->level == 1 && $doctorParent->doctorid != 47156) {
                                    $doctorName = UserDoctor::findOne(['userid' => $doctorParent->doctorid])->name;
                                    return '您已签约了“'.$doctorName.'”'."，如需更换签约社区请联系在线客服";
                                }

                                //未签约 或 签约了互联网社区医院（修改签约对象）
                                $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                                $doctorParent->doctorid = $doctor_id;
                                $doctorParent->parentid = $userid;
                                $doctorParent->level = 1;
                                $doctorParent->createtime = time();
                                $doctorParent->save();
                                //已注册用户直接签约成功

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
                            //$this->custom_send($openid);

                            return "签约成功，请点击卡片进入小程序添加宝宝/孕妇完善签约";
                        }
                    }
                    return "您好，感谢关注儿宝宝！\n\n如果管辖社区卫生服务中心已经开通签约儿保医生服务，回复SQ+社区名称即可获取社区二维码（如：“SQ小红门”，）长按并识别二维码即可签约成功并享受中医儿童健康指导，查看健康体检信息及通知，咨询儿保医生等服务\n\n如需要查询社区名称请访问：http://child.wedoctors.com.cn/doctors 查询社区后长按并识别二维码即可\n\n如果社区还没开通此项服务，点击菜单栏 -- 育儿服务 -- 添加宝宝信息,授权成功即可优先免费享有中医儿童健康指导服务";
                    break;
                case 'text':

                    $str = strtolower(mb_substr($message['Content'], 0, 2, 'utf-8'));
                    if($str == 'sq'){
                        $docName = substr($message['Content'], 2);
                        if ($docName) {
                            $query = UserDoctor::find();
                            if ($docName) {
                                $query->andFilterWhere(['like', 'name', $docName]);
                            }
                            $doctors = $query->orderBy('appoint desc')->all();
                            $docName = urlencode($docName);

                            if (count($doctors) > 1) {
                                $text = "查询到多个结果请访问链接查看:http://child.wedoctors.com.cn/doctors?search={$docName}";
                            } else {
                                $text = "请访问：http://child.wedoctors.com.cn/doctors?search={$docName} 查询结果";

                            }
                        }
                    }elseif ($message['Content'] == '成人疫苗') {
                        $text = "预约成人疫苗请点击下方链接，选择社区后预约:http://web.child.wedoctors.com.cn/wappoint";
                    }else{
                        $text = "宝宝家长，您在社区医院遇到体检查看问题，疫苗预约、体检预约、健康指导等问题时。可以点击公众号底栏进入儿宝宝小程序，点击我的，找到在线客服帮您进行解答。";
                    }
                    return $text;
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        $response = $app->server->serve();
        $response->send();
        exit;

    }

    //客服消息
    public function custom_send($openid)
    {
        $weOpenid=WeOpenid::findOne(['openid'=>$openid]);
        if($weOpenid && $weOpenid->doctorid==442975){
            $data=[
                "touser"=>'o5ODa0451fMb_sJ1D1T4YhYXDOcg',
                "msgtype"=>"text",
                "text"=>
                    [
                        "content"=>"宝宝家长您好。\n请您务必添加医生小助手！长按自动识别医生小助手微信号（erbbxzs）。\n医生小助手会帮您解答：体检结果查看、疫苗预约、体检预约、健康指导、健康宣教、健康咨询等问题。"
                    ]
            ];
            ///WechatSendTmp::sendMessage($data);
        }
        return;

        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $app->customer_service->message("儿宝宝为您准备了免费的产后恢复课程、亲子游泳体验，请到<a href=\"http://www.qq.com\" data-miniprogram-appid=\"wx6c33bfd66eb0a4f0\" data-miniprogram-path=\"pages/index/index\">儿宝宝福利社</a>中领取")->to($openid)->send();

    }

    //创建菜单
    public function actionCreateMenu()
    {
        $buttons = [
            ['type' => 'miniprogram', 'name' => '育儿服务', 'url' => 'pages/index/index', 'appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',],
            ['type' => 'view', 'name' => '成人预约', 'sub_button' => [
                [
                    'type' => 'view',
                    'name' => '国产HPV二价预约',
                    'url' => 'http://web.child.wedoctors.com.cn/wappoint/index?type=1',
                ],
                [
                    'type' => 'view',
                    'name' => 'HPV九价预约',
                    'url' => 'http://web.child.wedoctors.com.cn/wappoint/index?vaccine=3',
                ],
                [
                    'type' => 'view',
                    'name' => '成人疫苗预约',
                    'url' => 'http://web.child.wedoctors.com.cn/wappoint/index',
                ],
                [
                    'type' => 'view',
                    'name' => '两癌筛查预约',
                    'url' => 'http://web.child.wedoctors.com.cn/qappoint/index',
                ],
                [
                    'type' => 'view',
                    'name' => '"冬病夏治三伏贴"在线预约',
                    'url' => 'http://web.child.wedoctors.com.cn/sappoint/index',
                ],

            ]],
            ['type' => 'view', 'name' => '我的', 'sub_button' => [
                // [
                //     'type' => 'view',
                //     'name' => '流行病学调查表',
                //     'url' => 'http://web.child.wedoctors.com.cn/question-naire/form?id=1',
                // ],
//                [
//                    'type' => 'view',
//                    'name' => '问医生',
//                    'url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1147c2e491dfdf1d&redirect_uri=http://web.child.wedoctors.com.cn/haodf&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect',
//                ],

                [
                    'type' => 'miniprogram',
                    'name' => '宝宝预约记录',
                    'url' => Yii::$app->params['index_url'],
                    'appid' => \Yii::$app->params['wxXAppId'],
                    'pagepath' => 'pages/appoint/my'
                ],
                                [
                    'type' => 'view',
                    'name' => '成人预约记录',
                    'url' => 'http://web.child.wedoctors.com.cn/wappoint/my',
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
                // [
                //     'type' => 'miniprogram',
                //     'name' => '我的福利',
                //     'url' => Yii::$app->params['index_url'],
                //     'appid' => 'wx6c33bfd66eb0a4f0',
                //     'pagepath' => 'pages/index/index'
                // ],
                [
                    'type' => 'view',
                    'name' => '24小时视频医生',
                    'url' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx7162935a1a261323&redirect_uri=https%3A%2F%2Fvd.lequnmed.club%2Fboli_vd_wechat%2F100%2Ftop&response_type=code&scope=snsapi_userinfo&connect_redirect=1#wechat_redirect'
                ],
            ]],

        ];
        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        // $accessToken = $app->access_token; // EasyWeChat\Core\AccessToken 实例
        // $token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.        
        // $app->access_token->setToken((string)$token);

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
