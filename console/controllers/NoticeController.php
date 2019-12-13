<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/12/11
 * Time: 下午2:14
 */

namespace console\controllers;


use common\helpers\WechatSendTmp;
use common\models\Autograph;
use common\models\Examination;
use common\models\UserDoctor;
use common\models\UserLogin;
use yii\base\Controller;

class NoticeController extends Controller
{
    public function actionRenew()
    {

        $auto = Autograph::find()->where(['endtime'=>date('Ymd',strtotime('-1 week'))])->all();

        $temp = 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE';
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=757",
        ];
        foreach ($auto as $k => $v) {
            var_dump($v->endtime);
//
//            $hospital=UserDoctor::findOne(['userid'=>$v->doctorid]);
//            $data = [
//                'first' => array('value' => "家庭医生签约服务续约通知\n",),
//                'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
//                'keyword2' => ARRAY('value' => "您签约的".$hospital->name."即将到期，根据签约合同到期之后将自动续约，如有任何疑问可联系儿宝小助手微信（erbbzs）"),
//                'remark' => ARRAY('value' => "\n 点击查看官方通知详情", 'color' => '#221d95'),
//            ];
//            $userLogin = UserLogin::findAll(['userid' => $v->userid]);
//            if ($userLogin) {
//                foreach ($userLogin as $ulk => $ulv) {
//                    if ($ulv->openid) {
//                        $rs = WechatSendTmp::send($data, $ulv->openid, $temp, '', $miniprogram);
//                    }
//                }
//            }
        }
    }

    public function actionEx()
    {


    }

}