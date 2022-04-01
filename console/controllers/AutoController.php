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
        $auto = Autograph::find()->where(['<','endtime',date('Ymd')])->all();

//        var_dump(date('Ymd',strtotime('-1 week')));
//        var_dump($auto);exit;
        $temp = 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE';
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=757",
        ];
        foreach ($auto as $k => $v) {
            $v->endtime=date('Ymd',strtotime('+1 year',strtotime($v->endtime)));
            $v->save();
            echo count($auto)."-".$v->endtime;
            echo "\n";
        }
    }
}