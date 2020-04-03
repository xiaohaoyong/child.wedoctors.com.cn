<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\modules\v3\controllers;

use common\components\Code;
use common\models\Appoint;
use common\models\ChildInfo;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointWeek;
use common\models\Vaccine;

class AppointController extends \api\modules\v2\controllers\AppointController
{
    public function actionDayNum($doctorid, $week, $type, $day)
    {
        $rs = [];
        $appoint = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        if ($appoint) {
            $weeks = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $appoint->id])->orderBy('time_type asc')->all();
            if ($weeks) {
                $appoints = Appoint::find()
                    ->select('count(*)')
                    ->andWhere(['type' => $type])
                    ->andWhere(['doctorid' => $doctorid])
                    ->andWhere(['appoint_date' => strtotime($day)])
                    ->andWhere(['!=', 'state', 3])
                    ->andWhere(['mode' => 0])
                    ->indexBy('appoint_time')
                    ->groupBy('appoint_time')
                    ->column();
                foreach ($weeks as $k => $v) {
                    if ($appoints) {
                        $num = $v->num - $appoints[$v->time_type];
                        $rs[$v->time_type] = $num > 0 ? $num : 0;
                    } else {
                        $rs[$v->time_type] = $v->num;
                    }
                }
                if($appoint->updateInterval && $appoint->updateInterval>strtotime($day)){
                    if($appoint->interval==1){
                        $interval=2;
                    }else{
                        $interval=1;
                    }
                }else{
                    $interval=$appoint->interval;
                }
                return ['list'=>$rs,'interval'=>$interval];
            }
        }
        return new Code(20020, '未设置');
    }
}