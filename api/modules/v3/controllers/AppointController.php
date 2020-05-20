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
        return new Code(21000, '客户端已过期,请升级客户端');

        $rs = [];
        $times=[];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);


        $week=date('w',strtotime($day));
        $weekv = HospitalAppointVaccine::find()
            ->select('id')
            ->where(['haid' => $hospitalA->id])
            ->where(['week' => $week])->count();
        if($weekv>0){
            $weekvs[]=$week;
        }
        ;

        $is_appoint=$hospitalA->is_appoint(strtotime($day),$weekvs);
        if(!$is_appoint){
            return ['list' => [],'is_appoint'=>$is_appoint,'text'=>'非线上预约门诊日，请选择其他日期！'];
        }
        if($is_appoint==2){
            $date=date('Y年m月d日',strtotime('-'.HospitalAppoint::$cycleNum[$hospitalA->cycle].' day',strtotime($day)));
            return ['list' => [],'is_appoint'=>$is_appoint,'text'=>$date." ".HospitalAppoint::$rtText[$hospitalA->release_time]];
        }


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
                if($doctorid==4119 && date('Ymd',strtotime($day))=='20200615'){
                    foreach($rs as $k=>$v){
                        if(in_array($k,[4,5,6,13,14,15,16,17,18])){
                            $rs[$k]=0;
                        }
                    }
                }

                return ['list'=>$rs,'interval'=>$interval];
            }
        }
        return new Code(20020, '未设置');
    }
}