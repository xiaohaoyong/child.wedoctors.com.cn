<?php
/**
 * 预约就诊评价推送消息
 * 预约日期当天已完成的就诊单 18点推送 评价消息
 */

namespace console\controllers;

use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointComment;
use common\models\UserLogin;
use common\models\UserDoctor;

class AppointCommentController extends \yii\console\Controller
{
    public function actionAmessage()
    {
        $t_td=date("Y-m-d");
        $t_tds=time();
        $ap_qy=Appoint::find()->andWhere(['state'=>2])->andWhere(['>=','appoint_date',$t_td])->andWhere(['<=','appoint_date',$t_tds]);
        $ap_da=$ap_qy->asArray()->all();
        foreach ($ap_da as $v){
            $is_d=AppointComment::find()->where(['aid' => $v['id']])->one();
            if(!$is_d) { //未评价才可以推送
                $openid = UserLogin::getOpenid($v['userid']);
                $doctor = UserDoctor::find()->where(['userid' => $v['doctorid']])->one();
                $jz_int = date("Y-m-d H:i:s", $v['appoint_date']);
                $data = [
                    'first' => ['value' => "感谢您的信任，请点击完成您在医院就诊后满意度调查"],
                    'keyword1' => array('value' => $doctor->name),
                    'keyword2' => array('value' => $jz_int),
                    'remark' => array('value' => "点击详情反馈您的就诊体验，这将帮助我们更好的为您服务，感谢您的配合"),
                ];
                $url = "pages/evaluate/index?id=".$v['id'];
                WechatSendTmp::send($data,$openid,'1tLWy0T7zLQ9WC4I1YR4YvuSwHlEkmNJyOB5ww0CS8A',$url);
            }
        }
    }
}