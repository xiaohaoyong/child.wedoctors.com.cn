<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/6/14
 * Time: 下午2:00
 */

namespace docapi\controllers;


use common\models\Appoint;
use common\models\ChildInfo;
use common\models\Hospital;
use common\models\UserDoctor;

class AppointController extends Controller
{
    public function actionView($id){
        $appoint=Appoint::findOne(['id'=>$id]);

        if($appoint)
        {
            $appoint->state=2;
            $appoint->save();
            $row = $appoint->toArray();
            $doctor = UserDoctor::findOne(['userid' => $appoint->doctorid]);
            if ($doctor) {
                $hospital = Hospital::findOne($doctor->hospitalid);
            }
            $row['hospital'] = $hospital->name;
            $row['type'] = Appoint::$typeText[$appoint->type];
            $row['time'] = date('Y.m.d', $appoint->appoint_date) . "  " . Appoint::$timeText[$appoint->appoint_time];
            $row['child_name'] = ChildInfo::findOne($appoint->childid)->name;
            $row['duan'] = $appoint->appoint_time;

            $index = Appoint::find()
                ->andWhere(['appoint_date' => $appoint->appoint_date])
                ->andWhere(['<', 'id', $id])
                ->andWhere(['doctorid' => $appoint->doctorid])
                ->andWhere(['appoint_time' => $appoint->appoint_time])
                ->andWhere(['type' => $appoint->type])
                ->count();
            $row['index'] = $index + 1;
            return $row;
        }
    }

}