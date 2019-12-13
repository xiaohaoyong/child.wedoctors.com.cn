<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/12/13
 * Time: 上午10:27
 */

namespace console\controllers;

use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\Autograph;
use common\models\UserDoctor;
use common\models\UserLogin;
use yii\base\Controller;

class AutoController extends Controller
{
    public function actionRenew()
    {

        $log=new Log('AutographRenew');
        $auto = Autograph::find()->where(['endtime'=>date('Ymd',strtotime('-1 week'))])->all();

//        var_dump(date('Ymd',strtotime('-1 week')));
//        var_dump($auto);exit;
        $temp = 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE';
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=757",
        ];
        foreach ($auto as $k => $v) {
            var_dump($v->endtime);
            $log->addLog($v->userid);
            $hospital=UserDoctor::findOne(['userid'=>$v->doctorid]);
            $data = [
                'first' => array('value' => "家庭医生签约服务续约通知",),
                'keyword1' => ARRAY('value' => date('Y年m月d日 H:i'),),
                'keyword2' => ARRAY('value' => "您签约的".$hospital->name."即将到期 \n签约日期：".date('Y年m月d日',$v->createtime)."\n到期日期：".date('Y年m月d日',strtotime($v->endtime))."\n根据签约合同到期之后将自动续约"),
                'remark' => ARRAY('value' => "如有任何疑问可联系儿宝小助手微信（erbbzs）点击查看详情", 'color' => '#221d95'),
            ];
            $userLogin = UserLogin::findAll(['userid' => $v->userid]);
            if ($userLogin) {
                foreach ($userLogin as $ulk => $ulv) {
                    $log->addLog($ulv->openid);
                    if ($ulv->openid) {
                        $rs = WechatSendTmp::send($data, $ulv->openid, $temp, '', $miniprogram);
                    }
                }
            }
            $log->saveLog();
        }
    }
}