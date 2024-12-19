<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\modules\v4\controllers;

use common\components\Code;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\ChildInfo;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointMonth;
use common\models\HospitalAppointStreet;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointVaccineTimeNum;
use common\models\HospitalAppointWeek;
use common\models\Pregnancy;
use common\models\Street;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\Vaccine;

class AppointController extends \api\modules\v3\controllers\AppointController
{
    public function actionDoctor($id)
    {
        $uda = UserDoctor::findOne(['userid' => $id]);
        $row = $uda->toArray();
        if(strpos($uda->appoint,',')!==false){
            $types = explode(',',$uda->appoint);
        }elseif ($uda->appoint) {
            $types = str_split((string)$uda->appoint);
        }
        $hospital = Hospital::findOne($uda->hospitalid);
        $hospitalAppoint = HospitalAppoint::find()->where(['doctorid' => $id])->all();
        $har = [];
        foreach ($hospitalAppoint as $k => $v) {
            $hars = $v->toArray();
            $week = str_replace([1, 2, 3, 4, 5, 6, 0], ['一', '二', '三', '四', '五', '六', '七'], $v->weeks);
            $hars['weekStr'] = implode(',', preg_split('/(?<!^)(?!$)/u', $week));
            $hars['release_time'] = HospitalAppoint::$rtText[$v->release_time];
            $hars['cycleDay'] = HospitalAppoint::$cycleNum[$v->cycle];
            $har[$v->type] = $hars;
        }
        $row['hospital'] = $hospital->name;
        $row['hospitalAppoint'] = $har;

        $typeInfo[1] = '身高、体重、头围、面色...';
        $typeInfo[2] = '乙肝疫苗、卡介苗、百日破疫苗...';
        $typeInfo[3] = '微量元素检查，锌、铁、钙...';
        $typeInfo[4] = '';
        $typeInfo[5] = '建母子手册预约必须怀孕满6周...';
        $typeInfo[6] = '须在本社区管辖片区内方可领取';
        $typeInfo[8] = '入托体检是宝宝上幼儿园前的必要检查';


        foreach (HospitalAppoint::$typeText as $k => $v) {
            if ($k == 4 or $k==7 or $k==9 or $k==11) continue;
            $rs['id'] = $k;
            $rs['name'] = $v;
            $rs['info'] = $typeInfo[$k];
            $hospitalAppointis = HospitalAppoint::find()->where(['doctorid' => $id,'type'=>$k])->one();

            $rs['is_type'] = in_array($k, $types) && $hospitalAppointis ? 1 : 0;
            $typel[] = $rs;
        }
        $row['types'] = $typel;

        return $row;
    }

    public function actionForm($id, $type, $vid = 0,$sid=0)
    {
        if($type==5 || $type==6) {
            $gravida = Pregnancy::find()->where(['familyid' => $this->userid])->orderBy('id desc')->one();
            if(!$gravida->field1 ||!$gravida->field4 ||!$gravida->field11 ||!$gravida->field90 || !$gravida->field7 || !$gravida->field39 || !$gravida->field10){
                $gravida_is=1;
            }
        }elseif($type!=10){
            if($this->userid) {
                $childs = ChildInfo::find()->select('id,name,birthday,userid,field27')->where(['userid' => $this->userid])->all();
                foreach ($childs as $k => $v) {
                    $rs1 = $v->toArray();
                    $idcard = str_replace('*', '', $rs1['field27']);
                    $idcard1 = str_replace('*', '', $rs1['idcard']);

                    $rs1['field27'] = $idcard || $idcard1 ? 1 : 0;
                    $rss[] = $rs1;
                }
                $childs = $rss;
            }
        }

        //doctor
        $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);
        $userDoctor = UserDoctor::findOne(['userid' => $id]);
        if(strpos($userDoctor->appoint,',')!==false){
            $types = explode(',',$userDoctor->appoint);
        }elseif ($userDoctor->appoint) {
            $types = str_split((string)$userDoctor->appoint);
        }
        if ($appoint && in_array($type, $types)) {

            $phone = $this->userLogin->phone;
            $phone = $phone ? $phone : $this->user->phone;

            $days = [];
            $delay = $appoint->delay;
            $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));

            $dweek = ['日', '一', '二', '三', '四', '五', '六'];
            $dateMsg = ['不可约', '可约', '未放号','约满'];


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
                    $vaccineWeek = $query->groupBy('week')->column();
                    //如该疫苗无法获取周几可约则视为非法访问
                    if (!$vaccineWeek) {
                        return new Code(20010, '社区医院暂未开通服务！');
                    }
                }
            }

            //判断所选社区都有周几可约
            if ($sid) {
                $streetView = Street::findOne($sid);
                if ($streetView) {
                    $query = HospitalAppointStreet::find()
                        ->select('week')
                        ->where(['haid' => $appoint->id]);
                    $query->andWhere(['or', ['street' => $sid], ['street' => 0]]);
                    $streetWeek = $query->groupBy('week')->column();
                    //如该疫苗无法获取周几可约则视为非法访问
                    if (!$streetWeek) {
                        return new Code(20010, '社区医院暂未开通服务！');
                    }
                }
            }

            if($vaccineWeek && $streetWeek){
                $weekr=array_intersect($vaccineWeek,$streetWeek);
            }elseif($streetWeek || $vaccineWeek){
                $weekr=$streetWeek?$streetWeek:$vaccineWeek;
            }


            for ($i = 1; $i <= 40; $i++) {
                $day = $day + 86400;
                $w = date('w', $day);


                $rs['dateState'] = $appoint->is_appoint($day, $weekr);
                $rs['dateMsg'] = $dateMsg[$rs['dateState']];
                if($rs['dateState']==1){
                    $totle = Appoint::find()
                    ->andWhere(['type' => $type])
                    ->andWhere(['doctorid' => $id])
                    ->andWhere(['appoint_date' => $day])
                    ->andWhere(['!=', 'state', 3])
                    ->andWhere(['mode' => 0])
                    ->count();
                    $weekTotle = HospitalAppointWeek::find()
                        ->where(['week'=>$w,'haid'=>$appoint->id])
                        ->sum('num');

                    if($totle>=$weekTotle){
                        $rs['dateState'] = 0;
                        $rs['dateMsg'] = "约满";
                    }
                }
                $rs['date'] = $day;
                $rs['day'] = date('m.d', $day);
                $rs['dateStr'] = date('Ymd', $day);
                $rs['week'] = date('w', $day);
                $rs['weekStr'] = $dweek[$rs['week']];
                
                $days[] = $rs;
            }

            $hospitalV = HospitalAppointVaccine::find()
                ->select('vaccine')
                ->where(['haid' => $appoint->id])->groupBy('vaccine')->column();
            if ($hospitalV) {

                if (in_array(0, $hospitalV) && in_array(-1, $hospitalV)) {
                    $vQuery = Vaccine::find()->select('id,name,type')->andwhere(['adult' => 0])->andWhere(['alltype'=>0]);
                } else {
                    $vQuery = Vaccine::find()->select('id,name,type')->andWhere(['in', 'id', $hospitalV]);
                    if (in_array(-1, $hospitalV)) {
                        //查询所有二类疫苗
                        $Va = Vaccine::find()->select('id,name,type')->andWhere(['type' => 1]);
                    }
                    if (in_array(0, $hospitalV)) {
                        //查询所有一类类疫苗
                        $Va = Vaccine::find()->select('id,name,type')->andWhere(['type' => 0])->andWhere(['alltype'=>0]);
                    }
                    if ($Va) {
                        $vQuery->union($Va);
                    }
                }

                $vaccines = $vQuery->asArray()->all();

                foreach ($vaccines as $k => $v) {
                    $rs = $v;
                    $rs['name'] = $rs['name'] . "【" . Vaccine::$typeText[$rs['type']] . "】";
                    $rows[] = $rs;
                }
                $vaccines = $rows;
            } else {
                $vaccines = [];
            }


            //判断街道
            $hospitalS=HospitalAppointStreet::find()->select('street')
                ->where(['haid'=>$appoint->id])->groupBy('street')->column();
            if ($hospitalS) {
                $vQuery = Street::find()->select('id,title');
                if (!in_array(0, $hospitalS) && $hospitalS) {
                    $vQuery->andWhere(['in', 'id', $hospitalS]);
                }
                $street = $vQuery->asArray()->all();
                foreach ($street as $k => $v) {
                    $rs = $v;
                    $rs['name'] = $rs['title'] ;
                    $streetRows[] = $rs;
                }
                $streets = $streetRows;
            } else {
                $streets = [];
            }



            $monthType=[];
            if($appoint->type==1 && $appoint->is_month){
                $mt=HospitalAppointMonth::$typeText;

                $hospitalAppointMonth=HospitalAppointMonth::find()->select('type')->where(['haid'=>$appoint->id])->groupBy('type')->column();
                foreach($hospitalAppointMonth as $k=>$v){
                    if($mt[$v]){
                        $mrs['id']=$v;
                        $mrs['text']=$mt[$v];
                        $monthType[]=$mrs;
                    }
                }
            }


            return ['weekr'=>$weekr,'streets'=>$streets,'monthType'=>$monthType,'gravida_is'=>$gravida_is,'childs' => $childs,'gravida'=>$gravida,'phone' => $phone, 'vaccines' => $vaccines, 'days' => $days];

        } else {
            return new Code(20010, '社区医院暂未开通服务！');
        }

    }

    public function actionDayNum($doctorid, $week = 0, $type, $day, $vid = 0,$sid=0)
    {

        $rs = [];
        $times = [];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);

        $weekv=[];
        if($vid) {
            $weekv = HospitalAppointVaccine::find()
                ->select('week')
                ->where(['haid' => $hospitalA->id])
                ->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0], ['vaccine' => -1]])->groupBy('week')->column();
        }

        //判断所选社区都有周几可约
        if ($sid) {
            $streetView = Street::findOne($sid);
            if ($streetView) {
                $query = HospitalAppointStreet::find()
                    ->select('week')
                    ->where(['haid' => $hospitalA->id]);
                $query->andWhere(['or', ['street' => $sid], ['street' => 0]]);
                $streetWeek = $query->groupBy('week')->column();
                //如该疫苗无法获取周几可约则视为非法访问
                if (!$streetWeek) {
                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }

        if($weekv && $streetWeek){
            $weekr=array_intersect($weekv,$streetWeek);
        }elseif($streetWeek || $weekv){
            $weekr=$streetWeek?$streetWeek:$weekv;
        }


        $is_appoint = $hospitalA->is_appoint(strtotime($day), $weekr);
        if (!$is_appoint) {
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => '非线上预约门诊日，请选择其他日期！'];
        }
        if ($is_appoint == 2) {
            $d=HospitalAppoint::$cycleNum[$hospitalA->cycle]+$hospitalA->delay;
            $date = date('Y年m月d日', strtotime('-' .$d. ' day', strtotime($day)));
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => $date . " " . HospitalAppoint::$rtText[$hospitalA->release_time]];
        }
        $week = date('w', strtotime($day));

        $vaccine_count=Appoint::find()->where(['vaccine'=>$vid,'appoint_date'=>strtotime($day),'doctorid'=>$doctorid])->andWhere(['<','state',3])->count();
        $hospitalAppointVaccineNum=HospitalAppointVaccineNum::findOne(['haid'=>$hospitalA->id,'week'=>$week,'vaccine'=>$vid]);
        if($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num-$vaccine_count<=0){
            return ['list' => [], 'is_appoint' => 0, 'text' =>'此疫苗'.date('Y年m月d日',strtotime($day))."已约满，请选择其他日期".($hospitalAppointVaccineNum->num-$vaccine_count)];
        }



        //判断预约时间段类型
        $firstAppoint = Appoint::find()
            ->andWhere(['type' => $type])
            ->andWhere(['doctorid' => $doctorid])
            ->andWhere(['appoint_date' => strtotime($day)])
            ->andWhere(['!=', 'state', 3])
            ->andWhere(['mode' => 0])
            ->orderBy('createtime desc')
            ->one();
        $wquery = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $hospitalA->id]);

        if($firstAppoint) {
            if ($firstAppoint->appoint_time < 7) {
                $wquery->andWhere(['<', 'time_type', 7]);
            }
            if ($firstAppoint->appoint_time > 6) {
                $wquery->andWhere(['>', 'time_type', 6]);
            }
        }else{
            if($hospitalA->interval==1){
                $wquery->andWhere(['<', 'time_type', 7]);

            }
            if($hospitalA->interval==2){
                $wquery->andWhere(['>', 'time_type', 6]);
            }
        }

        $weeks=$wquery->all();

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
                }else{
                    $rs[$v->time_type] = $v->num;
                }
                $rs_num[$v->time_type] = $v->num;
            }

            if ($doctorid != 176156) {
                unset($rs[19]); unset($rs[20]);
            }


            $vWeek=[];
            $vTypes=[];
            //获取疫苗预约时间（上午/下午）
            if($vid) {
                $vaccine = Vaccine::findOne($vid);
                $query = HospitalAppointVaccine::find()
                    ->where(['haid' => $hospitalA->id,'week' => $week]);

                if ($vaccine->type == 0) {
                    $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0]]);
                } else {
                    $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => -1]]);
                }
                $vWeek = $query->select('type')->column();

                //如果该日期只设置了上午则代表设置的上午疫苗全天可约
                $vTypes = HospitalAppointVaccine::find()->select('type')
                    ->where(['haid' => $hospitalA->id,'week' => $week])->column();
            }

            //判断所选疫苗是否按照时间段分配
            if($vid){
                $hospitalAppointVaccineTimeNum=HospitalAppointVaccineTimeNum::find()
                    ->where(['vaccine'=>$vid,'week'=>$week,'type'=>$type,'doctorid'=>$doctorid])
                    ->select('num')
                    ->indexBy('appoint_time')
                    ->column();
                if($hospitalAppointVaccineTimeNum) {
                    //获取当前时间段该疫苗已预约数量
                    $appointVaccineNum = Appoint::find()
                        ->select('count(*)')
                        ->andWhere(['type' => $type])
                        ->andWhere(['doctorid' => $doctorid])
                        ->andWhere(['appoint_date' => strtotime($day)])
                        ->andWhere(['vaccine' => $vid])
                        ->andWhere(['!=', 'state', 3])
                        ->andWhere(['mode' => 0])
                        ->indexBy('appoint_time')
                        ->groupBy('appoint_time')
                        ->column();
                }
            }

            foreach ($rs as $k => $v) {
                if(in_array($k,[1,2,3,7,8,9,10,11,12,19,20]) && !in_array(1,$vWeek) && $vWeek){
                    $v=0;
                }elseif(in_array($k,[4,5,6,13,14,15,16,17,18]) && !in_array(2,$vWeek) && in_array(2,$vTypes)&& $vWeek){
                    $v=0;
                }
                $num=$v;

                if($hospitalAppointVaccineTimeNum){
                    if($hospitalAppointVaccineTimeNum[$k]) {
                        if($v>=($hospitalAppointVaccineTimeNum[$k]-$appointVaccineNum[$k])) {
                            $num = ($hospitalAppointVaccineTimeNum[$k]-$appointVaccineNum[$k]);
                        }
                    }else{
                        $num = 0;
                    }
                }
                $rows['time'] = Appoint::$timeText[$k];
                $rows['appoint_time'] = $k;
                $rows['num'] = $num;
                $rows['num1']=$rs_num[$k];
                if($rows['num1']==0){
                    $rows['txt']='无号';
                }else if($rows['num1']>0 && $rows['num']<1){
                    $rows['txt']='约满';
                }else if($rows['num']>0){
                    $rows['txt']='有号';
                }

                $times[] = $rows;
            }

            return ['list' => $times, 'is_appoint' => $is_appoint, 'text' => ''];

        }
        return new Code(20020, '未设置');
    }

    public function actionSave()
    {
        $post = \Yii::$app->request->post();
        $week = date('w', strtotime($post['appoint_date']));

        $doctor = UserDoctor::findOne(['userid' => $post['doctorid']]);
        if ($doctor) {
            $hospital = Hospital::findOne($doctor->hospitalid);
            if(strpos($doctor->appoint,',')!==false){
                $types = explode(',',$doctor->appoint);
            }elseif ($doctor->appoint) {
                $types = str_split((string)$doctor->appoint);
            }
        }
        if (!$doctor || !$doctor->appoint || !in_array($post['type'], $types)) {
            return new Code(21000, '社区未开通');
        };
        $appoint = HospitalAppoint::findOne(['doctorid' => $post['doctorid'], 'type' => $post['type']]);


        if(!isset($post['month']) && $appoint->is_month)
        {
            return new Code(21000, '您的版本过低，请更新版本后预约（点击左上角三个点选择重新进入小程序）');
        }


        $vaccine_count=Appoint::find()->where(['doctorid'=>$post['doctorid'],'vaccine'=>$post['vaccine'],'appoint_date'=>strtotime($post['appoint_date'])])->andWhere(['<','state',3])->count();
        $hospitalAppointVaccineNum=HospitalAppointVaccineNum::findOne(['haid'=>$appoint->id,'week'=>$week,'vaccine'=>$post['vaccine']]);
        if($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num-$vaccine_count<=0){
            return new Code(21000, '此疫苗'.date('Y年m月d日',strtotime($post['appoint_date']))."已约满，请选择其他日期");
        }

        //体检限制月龄预约
        if($post['type']==1 && $post['childid']){
            //获取社区设置 ：[2,3]表示社区设置的3月龄体检限制为大于2个月小于4个月
            $hospitalAppointMonth = HospitalAppointMonth::find()->select('month')
            ->where(['type' => $post['month']])->andWhere(['haid'=>$appoint->id])->orderBy('month asc')->column();

            //判断是否开通
            if($hospitalAppointMonth && $appoint->is_month) {
                //获取儿童生日
                $child = ChildInfo::findOne($post['childid']);
                if ($child) {
                    //提取社区设置最小月 first=2个月
                    $first = $hospitalAppointMonth[0];
                    //提取最大月龄 end=3个月
                    $end = $hospitalAppointMonth[count($hospitalAppointMonth) - 1];
                    $daytime =strtotime($post['appoint_date']);
                    //判断是否不在范围内  if((【体检日期减去最小月龄】小于 儿童生日) or 
                    //(【体检月龄减去最大月龄（+1表示 3-4个月之间都可以）】大于 儿童生日)) 如果不在则提示不符合条件
                    if (strtotime("-$first month", $daytime) < $child->birthday
                    || strtotime("-" . ($end + 1) . " month", $daytime) > $child->birthday) {
                        return new Code(20000,HospitalAppointMonth::$typeText[$post['month']]
                            ."，需宝宝在预约日期时满".$first."个月且小于".($end + 1)."个月");
                    }
                }
            }
        }

        //判断所选疫苗都有周几可约
        if ($post['vaccine']) {
            $vaccine = Vaccine::findOne($post['vaccine']);
            if ($vaccine) {
                $query = HospitalAppointVaccine::find()
                    ->select('week')
                    ->where(['haid' => $appoint->id]);
                if ($vaccine->type == 0) {
                    $query->andWhere(['or', ['vaccine' => $post['vaccine']], ['vaccine' => 0]]);
                } else {
                    $query->andWhere(['or', ['vaccine' => $post['vaccine']], ['vaccine' => -1]]);
                }
                $vaccineWeek = $query->groupBy('week')->column();
                //如该疫苗无法获取周几可约则视为非法访问
                if (!$vaccineWeek) {
                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }


        //判断所选社区都有周几可约
        if ($post['street']) {
            $streetView = Street::findOne($post['street']);
            if ($streetView) {
                $query = HospitalAppointStreet::find()
                    ->select('week')
                    ->where(['haid' => $appoint->id]);
                $query->andWhere(['or', ['street' => $post['street']], ['street' => 0]]);
                $streetWeek = $query->groupBy('week')->column();
                //如该疫苗无法获取周几可约则视为非法访问
                if (!$streetWeek) {
                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }

        if($vaccineWeek && $streetWeek){
            $weekr=array_intersect($vaccineWeek,$streetWeek);
        }elseif($streetWeek || $vaccineWeek){
            $weekr=$streetWeek?$streetWeek:$vaccineWeek;
        }

        $is_appoint = $appoint->is_appoint(strtotime($post['appoint_date']),$weekr);
        if ($is_appoint != 1) {
            return new Code(21000, '预约日期非门诊日或未到放号时间!请更新客户端查看！');
        }

        $firstAppoint = Appoint::find()
            ->andWhere(['type' => $post['type']])
            ->andWhere(['doctorid' => $doctor->userid])
            ->andWhere(['appoint_date' => strtotime($post['appoint_date'])])
            ->andWhere(['!=', 'state', 3])
            ->andWhere(['mode' => 0])
            ->orderBy('createtime desc')
            ->one();
        if ($firstAppoint) {
            if ($firstAppoint->appoint_time > 6 && $post['appoint_time'] < 7) {
                return new Code(21000, '预约日期非门诊日或未到放号时间!请更新客户端查看！');
            }
            if ($firstAppoint->appoint_time < 7 && $post['appoint_time'] > 6) {
                return new Code(21000, '预约日期非门诊日或未到放号时间!请更新客户端查看！');
            }
        }

        //判断选择疫苗和日期是否匹配
        if($post['vaccine']) {
            $vaccine = Vaccine::findOne($post['vaccine']);
            $type=$vaccine->type==1?-1:0;
            $vaccines = HospitalAppointVaccine::find()
                ->where(['haid' => $appoint->id])
                ->andwhere(['week' => $week])->andwhere(['or', ['vaccine' => $post['vaccine']], ['vaccine' => $type]])->column();
            if(!$vaccines){
                return new Code(21000, '此日期没有您选择的疫苗！请选择其他日期');
            }
        }


        $w = date("w", strtotime($post['appoint_date']));
        $weeks = HospitalAppointWeek::find()
            ->andWhere(['week' => $w])
            ->andWhere(['haid' => $appoint->id])
            ->andWhere(['time_type' => $post['appoint_time']])->one();


        $appointed = Appoint::find()
            ->andWhere(['type' => $post['type']])
            ->andWhere(['doctorid' => $post['doctorid']])
            ->andWhere(['appoint_date' => strtotime($post['appoint_date'])])
            ->andWhere(['appoint_time' => $post['appoint_time']])
            ->andWhere(['mode' => 0])
            ->andWhere(['<', 'state', 3])
            ->count();


        if (($weeks->num - $appointed) <= 0) {
            return new Code(21000, '该时间段已约满，请选择其他时间');
        }


        $appoint = Appoint::find()->where(['childid' => $post['childid'],'userid'=>$this->userid, 'type' => $post['type'], 'state' => 1])->andWhere(['!=','type',10])->one();
        if ($appoint) {
            return new Code(21000, '您有未完成的预约');
        } elseif (!$post['childid'] && $post['type']!=10) {
            return new Code(21000, '请选择宝宝');
        } else {

            $model = new Appoint();
            $post['appoint_date'] = strtotime($post['appoint_date']);
            $post['state'] = 1;
            $post['userid'] = $this->userid;
            $post['loginid'] = $this->userLogin->id;
            $model->load(["Appoint" => $post]);


            if ($model->save()) {
                //var_dump($doctor->name);
                $userLogin = $this->userLogin;
                if ($userLogin->openid) {

                    $child = ChildInfo::findOne($model->childid);

                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => $hospital->name),
                        'keyword3' => ARRAY('value' => date('Y-m-d', $model->appoint_date) . " " . Appoint::$timeText[$model->appoint_time]),
                        'keyword4' => ARRAY('value' => $child ? $child->name : ''),
                        'keyword5' => ARRAY('value' => $model->phone),
                        'keyword6' => ARRAY('value' => "预约成功"),
                        'keyword7' => ARRAY('value' => $model->createtime),
                        'keyword8' => ARRAY('value' => Appoint::$typeInfoText[$model->type]),
                    ];
                    $rs = WechatSendTmp::sendX($data, $userLogin->xopenid, 'Ejdm_Ih_W0Dyi6XrEV8Afrsg6HILZh0w8zg2uF0aIS0', '/pages/appoint/view?id=' . $model->id, $post['formid']);
                }

                return ['id' => $model->id];
            } else {
                return new Code(20010, implode(':', $model->firstErrors));
            }
        }
    }

    public function actionMonth($childid,$type){

    }
}