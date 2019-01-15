<?php
/**
 * 追访
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/13
 * Time: 下午6:13
 */

namespace console\controllers;


use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\DoctorParent;
use common\models\Interview;
use common\models\Notice;
use common\models\Pregnancy;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserLogin;
use yii\base\Controller;

class InterviewController extends Controller
{
    /**
     * 发送追访
     */
    public function actionPush(){

        $log=new Log('InterviewPush');
        $week=[
            1=>[78,],
            2=>[113,127],
            3=>[148,162],
            4=>[197,239],
            5=>[260,274],
        ];
        foreach($week as $k=>$v) {
            foreach ($v as $vv) {
                $field16 = strtotime(date('Y-m-d')) - ($vv * 3600 * 24);
                $preg = Pregnancy::find()->where(['field16' => $field16])->andWhere(['familyid'=>138986])->andWhere(['>', 'familyid', 0])->all();
                foreach ($preg as $pk => $pv) {
                    $log->addLog($k);
                    $log->addLog($vv);
                    $log->addLog(date('Ymd',$field16));
                    $log->addLog($pv->field1);
                    $pt_value = Interview::find()->select('pt_value')->where(['userid' => $pv->familyid])->column();
                    if (!array_intersect($pt_value, [1, 2, 3, 8])) {
                        $openid = UserLogin::getOpenid($pv->familyid);
                        $log->addLog($openid);
                        if ($openid) {
                            $doctorParent = DoctorParent::findOne(['parentid' => $pv->familyid]);
                            if ($doctorParent) {
                                $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
                                if ($doctor) {
                                    $data = [
                                        'first' => ['value' => $doctor->name . '提醒您'],
                                        'keyword1' => ARRAY('value' => $pv->field1),
                                        'keyword2' => ARRAY('value' => date('Y年m月d日')),
                                        'keyword3' => ARRAY('value' => "孕产妇" . Interview::$weekText[$k] . "模板"),
                                        'remark' => ARRAY('value' => "请您尽快完善追访调查，以便医生团队更好的管理"),

                                    ];
                                    $rs = WechatSendTmp::send($data, $openid, '_W9A8aHaqyBmY3v_RPh8a6HygmqGnaVZGmCflNSPNnw', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/index/index?id=' . $k,]);
                                    $log->addLog($rs?1:0);
                                }
                            }
                        }

                        Notice::setList($pv->familyid, 8,['title' => "医生发来一个追访模板", 'ftitle' => "请认真填写，以便医生更好的管理", 'id' => '/interview/index?id=' . $k]);


                    }else{
                        $log->addLog(implode(',',$pt_value));
                    }
                    $log->saveLog();
                }
            }
        }
        echo date('Y-m-d',time()-(78*3600*24));
    }
}