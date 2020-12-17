<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/20
 * Time: 下午3:48
 */

namespace frontend\controllers;


use api\models\ChildInfo;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\Vaccine;
use common\components\Code;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\HospitalAppointMonth;
use common\models\HospitalAppointStreet;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointVaccineTimeNum;
use common\models\HospitalAppointWeek;
use common\models\Street;
use common\models\UserDoctorAppoint;

class ApiAppointController extends ApiController
{
    public function actionHospitals($search=""){
        $query = UserDoctor::find();
        if ($search) {
            $query->andFilterWhere(['like', 'name', $search]);
        }
        $query->andWhere(['county'=>1102]);
        $doctors = $query->select('userid,name,phone,skilful,appoint_intro,hospitalid')->orderBy('appoint desc')->all();
        $docs = [];
        foreach ($doctors as $k => $v) {
            $rs = $v->toArray();
            $rs['name'] = Hospital::findOne($v->hospitalid)->name;
            $docs[] = $rs;
        }

        return $docs;
    }
    public function actionType($id){
        $uda = UserDoctor::find()->select('userid,name,phone,skilful,appoint_intro,hospitalid,appoint')->where(['userid' => $id])->one();
        $row = $uda->toArray();
        if ($uda->appoint) {
            $types = str_split((string)$uda->appoint);
        }
        $hospital = Hospital::findOne($uda->hospitalid);
        $hospitalAppoint = HospitalAppoint::find()->select('weeks,info,release_time,phone,cycle,type')->where(['doctorid' => $id])->andWhere(['in','type',$types])->all();
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



        foreach (HospitalAppoint::$typeText as $k => $v) {
            $rs['id'] = $k;
            $rs['name'] = $v;
            $rs['is_type'] = in_array($k, $types) ? 1 : 0;
            $typel[] = $rs;
        }
        $row['types'] = $typel;

        return $row;
    }

    public function actionForm($id, $type, $vid = 0,$sid=0){

        //doctor
        $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);
        $userDoctor = UserDoctor::findOne(['userid' => $id]);
        if ($userDoctor->appoint) {
            $types = str_split((string)$userDoctor->appoint);
        }
        if ($appoint && in_array($type, $types)) {
            $days = [];
            $delay = $appoint->delay;
            $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));

            $dweek = ['日', '一', '二', '三', '四', '五', '六'];
            $dateMsg = ['不可约', '可约', '未放号'];


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


            for ($i = 1; $i <= 60; $i++) {
                $day = $day + 86400;
                $rs['date'] = $day;
                $rs['day'] = date('m.d', $day);
                $rs['dateStr'] = date('Ymd', $day);
                $rs['week'] = date('w', $day);
                $rs['weekStr'] = $dweek[$rs['week']];
                $rs['dateState'] = $appoint->is_appoint($day, $weekr);
                $rs['dateMsg'] = $dateMsg[$rs['dateState']];
                $days[] = $rs;
            }

            $hospitalV = HospitalAppointVaccine::find()
                ->select('vaccine')
                ->where(['haid' => $appoint->id])->groupBy('vaccine')->column();
            if ($hospitalV) {

                if (in_array(0, $hospitalV) && in_array(-1, $hospitalV)) {
                    $vQuery = Vaccine::find()->select('id,name,type');
                } else {
                    $vQuery = Vaccine::find()->select('id,name,type')->andWhere(['in', 'id', $hospitalV]);
                    if (in_array(-1, $hospitalV)) {
                        //查询所有二类疫苗
                        $Va = Vaccine::find()->select('id,name,type')->andWhere(['type' => 1]);
                    }
                    if (in_array(0, $hospitalV)) {
                        //查询所有一类类疫苗
                        $Va = Vaccine::find()->select('id,name,type')->andWhere(['type' => 0]);
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
                if (!in_array(0, $hospitalS)) {
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
                $monthType=HospitalAppointMonth::$typeText;
            }


            return ['streets'=>$streets,'monthType'=>$monthType, 'vaccines' => $vaccines, 'days' => $days];

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
                $times[] = $rows;
            }

            return ['list' => $times, 'is_appoint' => $is_appoint, 'text' => ''];

        }
        return new Code(20020, '未设置');
    }
    public function actionView($id)
    {

        $appoint=Appoint::findOne(['id'=>$id]);

        $row=$appoint->toArray();
        $doctor=UserDoctor::findOne(['userid'=>$appoint->doctorid]);
        if($doctor){
            $hospital=Hospital::findOne($doctor->hospitalid);
        }
        $rs['hospital']=$hospital->name;
        $rs['type']=Appoint::$typeText[$appoint->type];
        $rs['time']=date('Y.m.d',$appoint->appoint_date)."  ".Appoint::$timeText[$appoint->appoint_time];
        $rs['name']=AppointAdult::findOne(['id'=>$appoint->childid])->name;


        $rs['duan']=$appoint->appoint_time;
        if($appoint->vaccine==-2){
            $rs['vaccineStr']='两癌筛查';
        }else {
            $vaccine = Vaccine::findOne($appoint->vaccine);
            $rs['vaccineStr'] = $vaccine ? $vaccine->name : '';
        }
        $index=Appoint::find()
            ->andWhere(['appoint_date'=>$appoint->appoint_date])
            ->andWhere(['<','id',$id])
            ->andWhere(['doctorid'=>$appoint->doctorid])
            ->andWhere(['appoint_time'=>$appoint->appoint_time])
            ->andWhere(['type' => $appoint->type])
            ->count();
        $row['index']=$index+1;

        return $rs;

    }

    public function actionMy($userid,$type=1,$state=1){
        $appoints = Appoint::findAll(['userid' => $userid,'type'=>$type,'state'=>$state,'mode'=>3]);
        $list=[];
        foreach($appoints as $k=>$v){
            $row=$v->toArray();
            $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
            if($doctor){
                $hospital=Hospital::findOne($doctor->hospitalid);
            }
            $rs['id']=$v->id;
            $rs['hospital']=$hospital->name;
            $rs['type']=Appoint::$typeText[$v->type];
            $rs['time']=date('Y.m.d',$v->appoint_date)."  ".Appoint::$timeText[$v->appoint_time];
            $rs['stateText']=Appoint::$stateText[$v->state];
            $rs['name']=AppointAdult::findOne(['id'=>$v->childid])->name;
            $list[]=$rs;
        }

        return $list;

    }
    public function actionSave(){
        $post = \Yii::$app->request->post();


        if(!$post['appoint_name'] || !$post['phone'] || !$post['sex']
            || !$post['doctorid'] || !$post['appoint_time'] || !$post['appoint_date'] || !$post['type'] || !$post['birthday'] || !$post['userid']){
            return new Code(20010, '提交失败，缺少参数');
        }

        $week = date('w', strtotime($post['appoint_date']));

        $doctor = UserDoctor::findOne(['userid' => $post['doctorid']]);
        if ($doctor) {
            $hospital = Hospital::findOne($doctor->hospitalid);
            if ($doctor->appoint) {
                $types = str_split((string)$doctor->appoint);
            }
        }
        if (!$doctor || !$doctor->appoint || !in_array($post['type'], $types)) {
            return new Code(21000, '社区未开通');
        };
        $appoint = HospitalAppoint::findOne(['doctorid' => $post['doctorid'], 'type' => $post['type']]);


        if(!isset($post['month']) && $appoint->is_month)
        {
            return new Code(21000, '您的版本过低，请更新版本后预约');
        }


        $vaccine_count=Appoint::find()->where(['doctorid'=>$post['doctorid'],'vaccine'=>$post['vaccine'],'appoint_date'=>strtotime($post['appoint_date'])])->andWhere(['<','state',3])->count();
        $hospitalAppointVaccineNum=HospitalAppointVaccineNum::findOne(['haid'=>$appoint->id,'week'=>$week,'vaccine'=>$post['vaccine']]);
        if($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num-$vaccine_count<=0){
            return new Code(21000, '此疫苗'.date('Y年m月d日',strtotime($post['appoint_date']))."已约满，请选择其他日期");
        }

        //体检限制月龄预约
        if($post['type']==1 && $post['birthday']){
            $hospitalAppointMonth = HospitalAppointMonth::find()->select('month')->where(['type' => $post['month']])->andWhere(['haid'=>$appoint->id])->orderBy('month asc')->column();

            if($hospitalAppointMonth && $appoint->is_month) {
                $birthday=strtotime($post['birthday']);
                $first = $hospitalAppointMonth[0];
                $end = $hospitalAppointMonth[count($hospitalAppointMonth) - 1];
                $daytime =strtotime($post['appoint_date']);
                if (strtotime("-$first month", $daytime) < $birthday || strtotime("-" . ($end + 1) . " month", $daytime) > $birthday) {
                    return new Code(21000,date('Y年m月d日',$daytime).HospitalAppointMonth::$typeText[$post['month']]
                        ."，需宝宝在预约日期时满".$first."个月且小于".($end + 1)."个月");
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

        $appointAdult=AppointAdult::findOne(['userid'=>$post['userid'],'name'=>$post['appoint_name']]);
        $appointAdult=$appointAdult?$appointAdult:new AppointAdult();
        $appointAdult->userid=$post['userid'];
        $appointAdult->name=$post['appoint_name'];
        $appointAdult->phone=$post['phone'];
        $appointAdult->gender=$post['sex'];
        //$appointAdult->birthday=strtotime($post['birthday']);
        if(!$appointAdult->save()){

            return ['code'=>21000,'msg'=>'联系人信息保存失败',$appointAdult->firstErrors];
        }


        $appoint=Appoint::findOne(['userid'=>$appointAdult->userid,'childid'=>$appointAdult->id,'type'=>$post['type'],'state'=>1,'mode'=>3]);

        if ($appoint) {
            return new Code(21000, '您有未完成的预约');
        }  else {

            $model = new Appoint();
            $post['appoint_date'] = strtotime($post['appoint_date']);
            $post['state'] = 1;
            $post['userid'] = $appointAdult->userid;
            $post['childid'] = $appointAdult->id;
            $post['mode'] =3;

            $model->load(["Appoint" => $post]);

            if ($model->save()) {
                return ['id' => $model->id];
            } else {
                return new Code(20010, implode(':', $model->firstErrors));
            }
        }
    }

    public function actionState($id, $cancel_type = 0,$userid)
    {
        $model = Appoint::findOne(['id' => $id, 'userid' => $userid,'mode'=>3]);
        if (!$model) {
            return new Code(20010, '取消失败！');
        } else {

            $model->state = 3;
            $model->cancel_type = $cancel_type;
            if ($model->save()) {

                return [];

            } else {
                return new Code(20011, implode(',', $model->firstErrors));
            }
        }
    }
}