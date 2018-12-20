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
use common\models\HospitalAppointWeek;

class AppointController extends \api\modules\v1\controllers\AppointController
{
    public function actionForm($id,$type){
        $childs=ChildInfo::findAll(['userid'=>$this->userid]);


        //doctor
        $appoint=HospitalAppoint::findOne(['doctorid'=>$id,'type'=>$type]);
        if($appoint) {

            $phone = $this->userLogin->phone;
            $phone = $phone ? $phone : $this->user->phone;
            $row=$appoint->toArray();

            $holiday=[
                '2018-12-30',
                '2018-12-31',
                '2019-1-1',
                '2019-2-4',
                '2019-2-5',
                '2019-2-6',
                '2019-2-7',
                '2019-2-8',
                '2019-2-9',
                '2019-2-10',
                '2019-4-5',
                '2019-4-6',
                '2019-4-7',
                '2019-5-1',
                '2019-6-7',
                '2019-6-8',
                '2019-6-9',
                '2019-9-13',
                '2019-9-14',
                '2019-9-15',
                '2019-10-1',
                '2019-10-2',
                '2019-10-3',
                '2019-10-4',
                '2019-10-5',
                '2019-10-6',
                '2019-10-7',
            ];
            return ['childs' => $childs, 'appoint' => $row, 'phone' => $phone,'holiday'=>$holiday];
        }else{
            return new Code(20010,'社区医院暂未开通服务！');
        }
    }
    public function actionDayNum($doctorid,$week,$type,$day){
        $rs=[];
        $appoint=HospitalAppoint::findOne(['doctorid'=>$doctorid,'type'=>$type]);
        if($appoint){
            $weeks=HospitalAppointWeek::find()->andWhere(['week'=>$week])->andWhere(['haid'=>$appoint->id])->orderBy('time_type asc')->all();
            if($weeks){
                $appoints=Appoint::find()
                    ->select('count(*)')
                    ->andWhere(['type'=>$type])
                    ->andWhere(['doctorid'=>$doctorid])
                    ->andWhere(['appoint_date'=>strtotime($day)])
                    ->andWhere(['!=','state',3])
                    ->andWhere(['mode'=>0])
                    ->indexBy('appoint_time')
                    ->groupBy('appoint_time')
                    ->column();
                foreach($weeks as $k=>$v){
                    if($appoints) {
                        $num = $v->num - $appoints[$v->time_type];
                        $rs[$v->time_type] = $num > 0 ? $num : 0;
                    }else{
                        $rs[$v->time_type] =$v->num ;
                    }
                }
                return $rs;
            }
        }
        return new Code(20020,'未设置');
    }
}