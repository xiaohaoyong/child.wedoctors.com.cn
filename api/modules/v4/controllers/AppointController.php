<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\modules\v4\controllers;

use common\components\Code;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\ChildInfo;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\Vaccine;

class AppointController extends \api\modules\v3\controllers\AppointController
{
    public function actionDoctor($id){
        $uda=UserDoctor::findOne(['userid'=>$id]);
        $row=$uda->toArray();
        if($uda->appoint){
            $types=str_split((string)$uda->appoint);
        }
        $hospital=Hospital::findOne($uda->hospitalid);
        $hospitalAppoint=HospitalAppoint::find()->where(['doctorid'=>$id])->all();
        $har=[];
        foreach($hospitalAppoint as $k=>$v){
            $hars=$v->toArray();
            $week=str_replace([1, 2, 3, 4, 5, 6, 0], ['一', '二', '三', '四', '五', '六', '七'], $v->weeks);
            $hars['weekStr']= implode(',',preg_split('/(?<!^)(?!$)/u', $week ));
            $hars['release_time']= HospitalAppoint::$rtText[$v->release_time];
            $hars['cycleDay']=HospitalAppoint::$cycleNum[$v->cycle];
            $har[$v->type]=$hars;
        }
        $row['hospital']=$hospital->name;
        $row['hospitalAppoint']=$har;

        $typeInfo[1]='身高、体重、头围、面色...';
        $typeInfo[2]='乙肝疫苗、卡介苗、百日破疫苗...';
        $typeInfo[3]='微量元素检查，锌、铁、钙...';
        $typeInfo[4]='';
        $typeInfo[5]='建母子手册预约必须怀孕满6周...';
        $typeInfo[6]='须在本社区管辖片区内方可领取';

        foreach(HospitalAppoint::$typeText as $k=>$v){
            if($k==4) continue;
            $rs['id']=$k;
            $rs['name']=$v;
            $rs['info']=$typeInfo[$k];

            $rs['is_type']=in_array($k,$types)?1:0;
            $typel[]=$rs;
        }
        $row['types']=$typel;

        return $row;
    }
    public function actionForm($id, $type,$vid=0)
    {
        {
            $childs = ChildInfo::findAll(['userid' => $this->userid]);


            //doctor
            $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);
            $userDoctor = UserDoctor::findOne(['userid' => $id]);
            if($userDoctor->appoint){
                $types=str_split((string)$userDoctor->appoint);
            }
            if ($appoint && in_array($type,$types)) {

                $phone = $this->userLogin->phone;
                $phone = $phone ? $phone : $this->user->phone;

                $days = [];
                $delay = $appoint->delay;
                $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));

                $dweek=['日','一','二','三','四','五','六'];
                $dateMsg=['非门诊','门诊日','未放号'];



                //判断所选疫苗都有周几可约
                if ($vid) {
                    $vaccine = Vaccine::findOne($vid);
                    if ($vaccine) {
                        $query = HospitalAppointVaccine::find()
                            ->select('week')
                            ->where(['haid' => $appoint->id]);
                        if ($vaccine->type == 0) {
                            $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0]]);
                        } else {
                            $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => -1]]);
                        }
                        $weekr = $query->groupBy('week')->column();
                        //如该疫苗无法获取周几可约则视为非法访问
                        if(!$weekr){
                            return new Code(20010,'社区医院暂未开通服务！');
                        }
                    }
                }



                for ($i = 1; $i <= 60; $i++) {
                    $day = $day + 86400;
                    $rs['date'] = $day;
                    $rs['day'] = date('m.d', $day);
                    $rs['dateStr']=date('Ymd',$day);
                    $rs['week'] = date('w', $day);
                    $rs['weekStr'] = $dweek[$rs['week']];
                    $rs['dateState']=$appoint->is_appoint($day,$weekr);
                    $rs['dateMsg']=$dateMsg[$rs['dateState']];
                    $days[] = $rs;
                }
                $hospitalV = HospitalAppointVaccine::find()
                    ->select('vaccine')
                    ->where(['haid' => $appoint->id])->groupBy('vaccine')->column();
                if ($hospitalV) {

                    if(in_array(0,$hospitalV) && in_array(-1,$hospitalV)){
                        $vQuery=Vaccine::find()->select('id,name,type');
                    }else{
                        $vQuery=Vaccine::find()->select('id,name,type')->andWhere(['in','id',$hospitalV]);
                        if(in_array(-1,$hospitalV)){
                            //查询所有二类疫苗
                            $Va=Vaccine::find()->select('id,name,type')->andWhere(['type'=>1]);
                        }
                        if(in_array(0,$hospitalV)){
                            //查询所有一类类疫苗
                            $Va=Vaccine::find()->select('id,name,type')->andWhere(['type'=>0]);
                        }
                        if($Va) {
                            $vQuery->union($Va);
                        }
                    }

                    $vaccines = $vQuery->asArray()->all();

                    foreach($vaccines as $k=>$v){
                        $rs=$v;
                        $rs['name']=$rs['name']."【".Vaccine::$typeText[$rs['type']]."】";
                        $rows[]=$rs;
                    }
                    $vaccines=$rows;
                } else {
                    $vaccines = [];
                }

                return ['childs' => $childs, 'phone' => $phone, 'vaccines' => $vaccines,'days'=>$days];

            } else {
                return new Code(20010,'社区医院暂未开通服务！');
            }
        }

    }

    public function actionDayNum($doctorid, $week=0, $type, $day,$vid=0)
    {
        $rs = [];
        $times=[];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        $is_appoint=$hospitalA->is_appoint(strtotime($day),$vid);
        if(!$is_appoint){
            return ['list' => [],'is_appoint'=>$is_appoint,'text'=>'非线上预约门诊日，请选择其他日期！'];
        }
        if($is_appoint==2){
            $date=date('Y年m月d日',strtotime('-'.HospitalAppoint::$cycleNum[$hospitalA->cycle].' day',strtotime($day)));
            return ['list' => [],'is_appoint'=>$is_appoint,'text'=>$date." ".HospitalAppoint::$rtText[$hospitalA->release_time]];
        }
        $week=date('w',strtotime($day));

        $weeks = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $hospitalA->id])->orderBy('time_type asc')->all();
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
            $firstAppoint=Appoint::find()
                ->andWhere(['type' => $type])
                ->andWhere(['doctorid' => $doctorid])
                ->andWhere(['appoint_date' => strtotime($day)])
                ->andWhere(['!=', 'state', 3])
                ->andWhere(['mode' => 0])
                ->orderBy('createtime desc')
                ->one();
            if($firstAppoint){
                foreach($rs as $k=>$v){
                    if($firstAppoint->appoint_time>6 && $k>6){
                        $rows['time']=Appoint::$timeText[$k];
                        $rows['appoint_time']=$k;
                        $rows['num']=$v;
                        $times[]=$rows;

                    }
                    if($firstAppoint->appoint_time<7 && $k<7){
                        $rows['time']=Appoint::$timeText[$k];
                        $rows['appoint_time']=$k;
                        $rows['num']=$v;
                        $times[]=$rows;

                    }
                }
            }else{
                foreach($rs as $k=>$v){
                    if($hospitalA->interval==2 && $k>6){
                        $rows['time']=Appoint::$timeText[$k];
                        $rows['appoint_time']=$k;
                        $rows['num']=$v;
                        $times[]=$rows;

                    }
                    if($hospitalA->interval==1 && $k<7){
                        $rows['time']=Appoint::$timeText[$k];
                        $rows['appoint_time']=$k;
                        $rows['num']=$v;
                        $times[]=$rows;
                    }
                }
            }
            return ['list' => $times,'is_appoint'=>$is_appoint,'text'=>''];

        }
        return new Code(20020, '未设置');
    }
}