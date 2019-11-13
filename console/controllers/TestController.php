<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/8/22
 * Time: 上午9:47
 */

namespace console\controllers;


use common\helpers\WechatSendTmp;
use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\WeOpenid;
use EasyWeChat\Factory;
use yii\base\Controller;

class TestController extends Controller
{
    public function actionAbc(){

//        $we=WeOpenid::find()->select('openid')->where(['>','createtime',strtotime('-2 hour')])->column();
//
//        foreach ($we as $k=>$v) {
//                   }

        $config = [
            'app_id' => 'wx1147c2e491dfdf1d',
            'secret' => '98001ba41e010dea2861f3e0d95cbb15',
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            //...
        ];

        $app = Factory::officialAccount($config);
        $app->customer_service->message("儿宝宝福利社上线啦，我们会定期给家长们搜罗些免费、实用、有趣的福利哦！<a href=\"http://www.qq.com\" data-miniprogram-appid=\"wx6c33bfd66eb0a4f0\" data-miniprogram-path=\"pages/index/index\">快快来领取吧</a>")->to('o5ODa09h1YVaoxutYCl4---NrwsM')->send();
        $app->customer_service->message('<image src="https://mmbiz.qpic.cn/mmbiz_jpg/vLIc50EG0zLcVawNoric18bzdMicmiaicqUwZfz1icf8QrbKtP8JGLbmFP7SM0HT6rIicfNFz5SI3UCu0jQRiaZEZEyiaA/0?wx_fmt=jpeg">')->to('o5ODa09h1YVaoxutYCl4---NrwsM')->send();


    }
    public function actionData(){
        $n=0;

        $doctorids=[];
        $openids=[];
        $doctorParent=\common\models\DoctorParent::find()->where(['level'=>1])->all();
        foreach ($doctorParent as $k => $v) {
            $openid=UserLogin::findOne(['userid'=>$v->parentid])->openid;
            $childInfo=ChildInfo::findOne(['userid'=>$v->parentid]);
            if(!$childInfo && $openid && !$openids[$openid] && $v->doctorid) {

                $doctor = $doctorids[$v->doctorid];
                $openids[$openid]=1;

                if (!$doctor) {
                    $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                    $doctorids[$v->doctorid] = $doctor;
                }

                $data = [
                    'first' => array('value' => "您好，为确保享受儿童中医药健康指导服务,请完善宝宝信息\n",),
                    'keyword1' => ARRAY('value' => "宝宝基本信息"),
                    'keyword2' => ARRAY('value' => $doctor->name),
                    'remark' => ARRAY('value' => "\n 点击授权并完善宝宝信息，如果已添加宝宝请忽略此条提醒", 'color' => '#221d95'),
                ];
                //var_dump($doctor->name);
                $rs = WechatSendTmp::send($data,"o5ODa0wc1u3Ihu5WvCVqoACeQ-HA", 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
                echo $openid."==";
                echo $rs['errcode'].$rs['errmsg'];
                echo "\n";
exit;
                usleep(300000);
            }
        }

        var_dump($n);
    }
    public function actionCreateZip(){
        $zipname='./article-' . date("Ymd") . '.zip';

        //$zipname = dirname(__ROOT__)."/static/childEducation/".$filename;

        $zip = new \ZipArchive();
        $res = $zip->open($zipname, \ZipArchive::OVERWRITE | \ZipArchive::CREATE);
        $zip->addFile("data/110567.csv","110567.csv");
        $zip->close();

    }
    public function actionChildType(){


        $doctorParent= DoctorParent::find()->select('parentid')->where(['doctorid'=>80198])->andFilterWhere(['level'=>1])->column();

        $lmount=date('Y-m-01');
        //该类型 本月已发送的儿童
        $articleUser=ArticleUser::find()->select('touserid')
            ->where(['child_type'=>5])
            //->andFilterWhere(['>','createtime',strtotime($lmount)])
            ->groupBy('childid')
            ->column();

        $users=array_diff($doctorParent,$articleUser);
        if($doctorParent) {
            $mouth = ChildInfo::getChildType(5);
            //var_dump($mouth);exit;
            $childCount = ChildInfo::find()->select('userid')->where(['>=', 'birthday', $mouth['firstday']])->andFilterWhere(['<=', 'birthday', $mouth['lastday']])->andFilterWhere(['in', 'userid', array_values($users)])->column();
        }
        var_dump(implode(',',$childCount));exit;

    }
    public function actionEmail(){

        $mbox = imap_open ("{imap.163.com:993}INBOX", "etzyxm@163.com", "rowyJdD2MD");
        var_dump($mbox);exit;


    }

}