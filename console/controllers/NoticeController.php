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
use common\models\UserLogin;
use yii\base\Controller;

class NoticeController extends Controller
{
    public function actionRenew()
    {

        $auto = Autograph::find()->where(['createtime']);



        $temp = 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE';
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=757",
        ];
        foreach ($auto as $k => $v) {
            $data = [
                'first' => array('value' => "家庭医生签约服务续约通知\n",),
                'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
                'keyword2' => ARRAY('value' => "您于".date('Y年m月d日',$v->createtime)."签约".$hospital."即将到期，到期之后将自动续约，如需解约，请联系签约社区解约。"),
                'remark' => ARRAY('value' => "\n 点击查看社区官方通知详情", 'color' => '#221d95'),
            ];
            $userLogin = UserLogin::findAll(['userid' => $v]);
            if ($userLogin) {
                foreach ($userLogin as $ulk => $ulv) {
                    if ($ulv->openid) {
                        $rs = WechatSendTmp::send($data, $ulv->openid, $temp, '', $miniprogram);
                    }
                }
            }
        }
    }

    public function actionEx()
    {


    }

}