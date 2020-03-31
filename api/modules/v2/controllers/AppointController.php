<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\modules\v2\controllers;

use common\components\Code;
use common\models\Appoint;
use common\models\ChildInfo;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointWeek;
use common\models\Vaccine;

class AppointController extends \api\modules\v1\controllers\AppointController
{
    public function actionForm($id, $type)
    {
        $childs = ChildInfo::findAll(['userid' => $this->userid]);


        //doctor
        $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);
        if ($appoint) {

            $phone = $this->userLogin->phone;
            $phone = $phone ? $phone : $this->user->phone;
            $row = $appoint->toArray();

            $holiday = [
                '2020-1-1',
                '2020-1-24',
                '2020-1-25',
                '2020-1-26',
                '2020-1-27',
                '2020-1-28',
                '2020-1-29',
                '2020-1-30',
                '2020-4-4',
                '2020-4-5',
                '2020-4-6',
                '2020-5-1',
                '2020-5-2',
                '2020-5-3',
                '2020-5-4',
                '2020-5-5',
                '2020-6-25',
                '2020-6-26',
                '2020-6-27',
                '2020-10-1',
                '2020-10-2',
                '2020-10-3',
                '2020-10-4',
                '2020-10-5',
                '2020-10-6',
                '2020-10-7',
                '2020-10-8',
            ];
            $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);
            $hospitalV = HospitalAppointVaccine::find()
                ->where(['haid' => $appoint->id])->all();

            if ($hospitalV) {
                $vaccines = Vaccine::find()->all();
            } else {
                $vaccines = [];
            }


            return ['childs' => $childs, 'appoint' => $row, 'phone' => $phone, 'holiday' => $holiday, 'vaccines' => $vaccines];
        } else {
            return new Code(20010, '社区医院暂未开通服务！');
        }
    }

    public function actionVaccines($id, $type, $vid)
    {
        $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);

        $vaccine = Vaccine::findOne($vid);
        if ($vaccine) {

            $query = HospitalAppointVaccine::find()
                ->select('week')
                ->where(['haid' => $appoint->id]);

            if($vaccine->source!=0){
                $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0]]);
            }else{
                $query->andWhere(['vaccine' => $vid]);
            }
            $hospitalV=$query->groupBy('week')->column();

        }
        return ['weeks' => $hospitalV];

    }

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


                return $rs;
            }
        }
        return new Code(20020, '未设置');
    }
}