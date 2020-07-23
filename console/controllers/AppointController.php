<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/22
 * Time: 下午3:24
 */

namespace console\controllers;


use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\UserLogin;
use yii\base\Controller;

class AppointController extends Controller
{
    public function actionOverdue(){

        $day=strtotime(date('Y-m-d'));
        Appoint::updateAll(['state' => 4], 'state=1 and appoint_date <='.$day);

    }
    public function actionNotice(){
        $day=strtotime('+1 day',strtotime(date('Y-m-d 00:00:00')));
        $appoint=Appoint::find()->where(['appoint_date'=>$day])->andWhere(['not in','doctorid',[221895]])->andWhere(['!=','state',3])->all();

        $log=new Log('appoint_notice');
        if($appoint) {
            foreach ($appoint as $k => $v) {
                $log->addLog($v->id);
                $log->addLog($v->userid);
                $openid=UserLogin::getOpenid($v->userid);
                $log->addLog($openid);

                if($openid) {
                    $temp = 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE';
                    $data = [
                        'first' => array('value' => "宝宝家长您好",),
                        'keyword1' => ARRAY('value' => date('Y年m月d'),),
                        'keyword2' => ARRAY('value' => '您预约了' . date('Y年m月d', $day) . ' ' . Appoint::$typeText[$v->type] . '，请按照预约时间到达社区'),
                        'remark' => ARRAY('value' => "点击此处填写流行病学调查表，目前北京应急响应级别调整为三级，请酌情填写流行病学调查表，根据不同社区工作安排可能需要您出示调查结果，调查结果可以在公众号底部菜单我的->流行病学调查表中查看", 'color' => '#221d95'),
                    ];
                    if(in_array($v->doctorid,[192821,206260,257888,184793,160226])){
                        $rs = WechatSendTmp::send($data, $openid, $temp, 'http://web.child.wedoctors.com.cn/question-naire/form?id=2  &doctorid=' . $v->doctorid);
                    }else{
                        $data['remark']='此消息为系统自动推送，如已取消请忽略。';
                        $rs = WechatSendTmp::send($data, $openid, $temp, '',['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/appoint/view?id='.$v->id,]);
                    }
                    $log->addLog($rs ? 'true' : 'false');
                    $log->saveLog();
                }
            }
        }
    }
}