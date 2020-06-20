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
        $day=strtotime('+2 day',strtotime(date('Y-m-d 00:00:00')));
        $appoint=Appoint::find()->where(['appoint_date'=>$day])->andWhere(['!=','state',3])->all();

        $log=new Log('appoint_notice');
        if($appoint) {
            foreach ($appoint as $k => $v) {
                $log->addLog($v->id);
                $log->addLog($v->userid);
                $openid=UserLogin::getOpenid($v->userid);
                $log->addLog($openid);

                $data = [
                    'first' => array('value' => "宝宝家长您好",),
                    'keyword1' => ARRAY('value' => date('Y年m月d'),),
                    'keyword2' => ARRAY('value' => '您预约了'.date('Y年m月d',$day).' '.Appoint::$typeText[$v->type].'，请按照预约时间到达社区'),
                    'remark' => ARRAY('value' => "鉴于目前北京市二级防控阶段，请点击此通知填写流行病学史调查表，根据不同社区工作安排可能需要您出示调查结果，调查结果可以在公众号底部菜单我的->病学史调查表中查看", 'color' => '#221d95'),
                ];
                $temp='AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE';
                $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', $temp, 'http://web.child.wedoctors.com.cn/question-naire/form?id=1&doctorid='.$v->doctorid);
                $log->addLog($rs?'true':'false');
                $log->saveLog();
                exit;
            }
        }
    }
}