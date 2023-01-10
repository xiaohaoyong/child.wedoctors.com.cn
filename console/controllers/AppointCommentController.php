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
				$thing1 = '就诊评价提醒';
				$thing2 = '感谢您的信任，请点击完成您在医院就诊后满意度调查';
				$data = [
					'thing1' => ARRAY('value' => $thing1),
					'thing2' => ARRAY('value' => $thing2),
					'time3'  => ARRAY('value' => date('Y年m月d日 H:i',time())),
				];
				$userLogin = UserLogin::find()->where(['userid'=>$v['userid']])->one();
				$rs=WechatSendTmp::sendSubscribe($data,$userLogin->xopenid,'cJqc11RdX95akxICJmQo3nP-0yo6VA4eHAeZHjEViHo','pages/evaluate/index?id='.$v['id']);
            }
        }
    }
}