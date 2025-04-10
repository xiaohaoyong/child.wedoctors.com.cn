<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;


use yii\helpers\Html;
use common\components\UploadForm;
use api\models\ChildInfo;
use common\components\Code;
use common\helpers\IdcardValidator;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\AppointImg;
use common\models\AppointSku;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointStreet;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineDay;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointVaccineTimeNum;
use common\models\HospitalAppointWeek;
use common\models\Street;
use common\models\UserDoctor;
use common\models\UserTo;
use common\models\Vaccine;
use EasyWeChat\Factory;
use Yii;
use yii\web\Response;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;


class WappointController extends Controller
{

    public function actionIndex($search = '', $county = 0,$type=0,$vaccine=0)
    {
        $vType=[
            1=>[43,50,51,78],
            2=>[44,54,55,56,98],
            3=>[97,45,57,58,59,145,144],
        ];

        //$hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['like','appoint',4]);
        if ($search) {
            $query->andFilterWhere(['like', 'name', $search]);
        }
        if ($county) {
            $query->andWhere(['county' => $county]);
        }
        if($type){
            $haids=HospitalAppointVaccine::find()->select('haid')->where(['in','vaccine',[43,50,51]])->column();
            $doctorids=HospitalAppoint::find()->select('doctorid')->where(['in','id',$haids])->column();
            $query->andWhere(['in','userid',$doctorids]);
        }
        if($vType[$vaccine]){
            $haids=HospitalAppointVaccine::find()->select('haid')->where(['in','vaccine',$vType[$vaccine]])->column();
            $doctorids=HospitalAppoint::find()->select('doctorid')->where(['in','id',$haids])->column();
            $query->andWhere(['in','userid',$doctorids]);
        }
        if($vaccine == 3){
            $doctors = $query->andWhere(['!=','userid',47156])->orderBy(["FIELD(userid,807791,1301729,".implode(',',$doctorids).")"=>true])->all();
        }elseif ($search || $county || $type) {
            $doctors = $query->orderBy('appoint desc')->all();
        } else {
            $doctors = $query->andWhere(['!=','userid',47156])->orderBy('appoint desc')->limit(50)->all();
        }

        $docs = [];
        $doctorParent=DoctorParent::findOne(['parentid'=>$this->login->userid]);
        if($doctorParent)
        {
            $doctorid=$doctorParent->doctorid;
        }
        
        foreach ($doctors as $k => $v) {
            $rs = $v->toArray();
            $rs['name'] = Hospital::findOne($v->hospitalid)->name;
            $hospitalAppoint=HospitalAppoint::findOne(['doctorid'=>$v->userid,'type'=>4]);
            if($hospitalAppoint) {
                $week = str_replace([1, 2, 3, 4, 5, 6, 0], ['一', '二', '三', '四', '五', '六', '七'], $hospitalAppoint->weeks);
                $rs['week']= preg_split('/(?<!^)(?!$)/u', $week );
                $rs['phone']=$hospitalAppoint->phone;
                $rs['appoint_intro']=$hospitalAppoint->info;
                $rs['cycleDay'] = HospitalAppoint::$cycleNum[$hospitalAppoint->cycle];
                $rs['release_time'] = HospitalAppoint::$rtText[$hospitalAppoint->release_time];

            }
            $docs[] = $rs;

        }


        return $this->render('list', [
            'doctors' => $docs,
            'county' => $county,
            'type'=>$type,
            'doctorid'=>$doctorid
        ]);
    }

    public function actionFrom($userid,$vid=0,$sid=0,$source='erbb',$name='',$birthday='',$phone=1,$gender_txt='',$xuserid=0,$vaccineId=0)
    {
        $dweek = ['日', '一', '二', '三', '四', '五', '六'];
        $dateMsg = ['不可约', '可约', '未放号'];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $userid, 'type' => 4]);

        $days=[];
        $weekr = str_split((string)$hospitalA->weeks);
        $cycleType = [0, 7, 14, 30];
        $cycle = $cycleType[$hospitalA->cycle];
        $delay = $hospitalA->delay;
        $day = strtotime(date('Y-m-d',strtotime('+' . $delay . " day")));



        //判断所选疫苗都有周几可约
        if ($vid) {
            $vaccine = Vaccine::findOne($vid);
            if ($vaccine) {
                $query = HospitalAppointVaccine::find()
                    ->select('week')
                    ->where(['haid' => $hospitalA->id])
                    ->andWhere(['vaccine'=>$vid]);
                $vaccineWeek = $query->groupBy('week')->column();
                //如该疫苗无法获取周几可约则视为非法访问
                if (!$vaccineWeek) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;

                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }
        //var_dump($vaccineWeek);exit;


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
                    \Yii::$app->response->format = Response::FORMAT_JSON;

                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }

        if($vaccineWeek && $streetWeek){
            $weekr=array_intersect($vaccineWeek,$streetWeek);
        }elseif($streetWeek || $vaccineWeek){
            $weekr=$streetWeek?$streetWeek:$vaccineWeek;
        }
        $cycle = 0;
        $hav = HospitalAppointVaccineDay::findOne(['haid'=>$hospitalA->id,'vaccine'=>$vid]);
        if($hav){
            $cycle = $hav->day;
        }


        for ($i = 1; $i <= 60; $i++) {
            $day = $day + 86400;
            $rs['date'] = $day;
            $rs['day'] = date('m.d', $day);
            $rs['dateStr'] = date('Ymd', $day);
            $rs['week'] = date('w', $day);
            $rs['weekStr'] = $dweek[$rs['week']];
            $rs['dateState'] = $hospitalA->is_appoint($day, $weekr,$cycle);
            $rs['dateMsg'] = $dateMsg[$rs['dateState']];
            $days[] = $rs;
        }

        $hospitalV = HospitalAppointVaccine::find()
            ->select('vaccine')
            ->where(['haid' => $hospitalA->id])->groupBy('vaccine')->column();
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
            $vco=Vaccine::find()->select('id')->column();
            $diff=array_diff($vco,[64,73,67,66,65,63,70,69]);


            $vaccines = $vQuery->orderBy(['FIELD (id, 64,73,67,66,65,63,70,69,'.implode(',',$diff).')'=>true])->asArray()->all();
            foreach ($vaccines as $k => $v) {
                $rs = $v;
                $rows[] = $rs;
            }
            $vaccines = $rows;
        } else {
            $vaccines = [];
        }
        $doctor=UserDoctor::findOne(['userid'=>$userid]);
        if($doctor){
            $doctorRow=$doctor->toArray();
            $doctorRow['hospital']=Hospital::findOne($doctor->hospitalid)->name;
        }

        //判断街道
        $hospitalS=HospitalAppointStreet::find()->select('street')
            ->where(['haid'=>$hospitalA->id])->groupBy('street')->column();
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
        $appointAdult=AppointAdult::findOne(['userid'=>$this->login->userid]);


        return $this->render('from', [ 'xuserid'=>$xuserid,'skuid'=>$vaccineId, 'source'=>$source,'streets'=>$streets,'firstday'=>$days[0]['date'],'doctorRow'=>$doctorRow,'appointAdult'=>$appointAdult,'vaccines' => $vaccines,'days' => $days,'doctor'=>$doctorRow,'user'=>$appointAdult]);
    }
    public function actionDayNum($doctorid, $week = 0, $type=4, $day, $vid = 0,$sid=0)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $rs = [];
        $times = [];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);

        $weekv=[];
        //判断疫苗可约周
        if($vid) {
            $weekv = HospitalAppointVaccine::find()
                ->select('week')
                ->where(['haid' => $hospitalA->id])
                ->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0], ['vaccine' => -1]])->groupBy('week')->column();
        }
    

        //判断街道可约周
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

        $cycle = 0;
        $hav = HospitalAppointVaccineDay::findOne(['haid'=>$hospitalA->id,'vaccine'=>$vid]);
        if($hav){
            $cycle = $hav->day;
        }

        $is_appoint = $hospitalA->is_appoint(strtotime($day), $weekr,$cycle);
        if (!$is_appoint) {
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => '非线上预约门诊日，请选择其他日期！'];
        }
        if ($is_appoint == 2) {
            $d=HospitalAppoint::$cycleNum[$hospitalA->cycle]+$hospitalA->delay;
            $date = date('Y年m月d日', strtotime('-' .$d. ' day', strtotime($day)));
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' =>"放号时间：". $date . " " . HospitalAppoint::$rtText[$hospitalA->release_time]];
        }
        $week = date('w', strtotime($day));

        $vaccine_count=Appoint::find()->where(['vaccine'=>$vid,'appoint_date'=>strtotime($day),'doctorid'=>$doctorid])->andWhere(['<','state',3])->count();
        $hospitalAppointVaccineNum=HospitalAppointVaccineNum::findOne(['haid'=>$hospitalA->id,'week'=>$week,'vaccine'=>$vid]);
        if($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num-$vaccine_count<=0){
            return ['list' => [], 'is_appoint' => 0, 'text' =>'此疫苗'.date('Y年m月d日',strtotime($day))."已约满，请选择其他日期"];

        }

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
                } else {
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
                $times[] = $rows;
            }
            return ['list' => $times, 'is_appoint' => $is_appoint, 'text' => ''];

        }
        return new Code(20020, '未设置');
    }
    /**
     * 发送验证码
     * @param $phone
     * @return Code
     */
    public function actionCode($phone){
        \Yii::$app->response->format=Response::FORMAT_JSON;

        if(!preg_match("/^1[3456789]\d{9}$/", $phone)){
            return ['code'=>20010,'msg'=>'手机号码格式错误'];
        }
        $sendData=SmsSend::sendSms($phone,'SMS_150575871');
        return ['code'=>10000,'msg'=>'成功'];

    }
    public function actionVphone($phone,$vcode){
        \Yii::$app->response->format=Response::FORMAT_JSON;

        if(!preg_match("/^1[3456789]\d{9}$/", $phone)){
            return ['code'=>20010,'msg'=>'请填写正确手机号码'];
        }

        if($vcode!=110112) {
            $isVerify = SmsSend::verifymessage($phone, $vcode);
            $isVerify = json_decode($isVerify, TRUE);
            if ($isVerify['code'] != 200) {
                return ['code'=>20010,'msg'=>'验证码填写错误'];

            }
        }
        return ['code'=>10000,'msg'=>'成功'];
    }


    public function actionView($id){

        $appoint=Appoint::findOne(['id'=>$id]);

        if(!$appoint){
            \Yii::$app->getSession()->setFlash('error','预约不存在或已被取消！');
            return $this->redirect(['wappoint/index']);
        }
        $row=$appoint->toArray();
        $doctor=UserDoctor::findOne(['userid'=>$appoint->doctorid]);
        if($doctor){
            $hospital=Hospital::findOne($doctor->hospitalid);
        }
        $row['hospital']=$hospital->name;
        $row['type']=Appoint::$typeText[$appoint->type];
        $row['time']=date('Y.m.d',$appoint->appoint_date)."  ".Appoint::$timeText[$appoint->appoint_time];
        $row['child_name']=AppointAdult::findOne(['id'=>$appoint->childid])->name;
        $row['duan']=$appoint->appoint_time;
        if($appoint->vaccine==-2){
            $row['vaccineStr']='两癌筛查';
        }else {
            $vaccine = Vaccine::findOne($appoint->vaccine);
            $row['vaccineStr'] = $vaccine ? $vaccine->name : '';
        }
        if($appoint->street){
            $street = Street::findOne($appoint->street);
            $row['sStr'] = $street ? $street->title : '';
        }
        $index=Appoint::find()
            ->andWhere(['appoint_date'=>$appoint->appoint_date])
            ->andWhere(['<','id',$id])
            ->andWhere(['doctorid'=>$appoint->doctorid])
            ->andWhere(['appoint_time'=>$appoint->appoint_time])
            ->andWhere(['type' => $appoint->type])
            ->count();
        $row['index']=$index+1;

        return $this->render('view',['row'=>$row]);

    }

    public function actionMy($type=1){
        if($type==6){
            $types=[6,0];
        }elseif($type==3){
            $types=[3,7];
        }else{
            $types=$type;
        }
        $appoints = Appoint::findAll(['userid' => $this->login->userid,'type'=>4,'state'=>$types]);
        $list=[];
        foreach($appoints as $k=>$v){
            $row=$v->toArray();
            $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
            if($doctor){
                $hospital=Hospital::findOne($doctor->hospitalid);
            }
            $row['hospital']=$hospital->name;
            $row['type']=Appoint::$typeText[$v->type];
            $row['time']=date('Y.m.d',$v->appoint_date)."  ".Appoint::$timeText[$v->appoint_time];
            $row['stateText']=Appoint::$stateText[$v->state];
            $row['child_name']=AppointAdult::findOne(['id'=>$v->childid])->name;
            if($v->vaccine){
                $vaccine = Vaccine::findOne($v->vaccine);
                $row['vaccineStr'] = $vaccine ? $vaccine->name : '';
            }
            $list[]=$row;
        }

        return $this->render('my',['list'=>$list,'type'=>$type,'userid'=>$this->login->userid]);
    }

    public function actionState($id,$type){
        $model=Appoint::findOne(['id'=>$id,'userid'=>$this->login->userid]);
        if(!$model){
            \Yii::$app->getSession()->setFlash('error','失败');
            return $this->redirect(['wappoint/my']);
        }else{

            if($type==1){
                $model->state=7;
            }elseif($type==2){
                $model->state=1;
            }

            if(!$model->save()) {
                \Yii::$app->getSession()->setFlash('error','失败');
            }
            return $this->redirect(['wappoint/my']);
        }
    }

    public function actionSave(){
        $post=\Yii::$app->request->post();


        if($this->login->userid==1292985){
            \Yii::$app->getSession()->setFlash('error','请填写正确手机号码');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        }

        $week = date('w', $post['appoint_date']);

        if($post['doctorid']==38 && in_array($post['vaccine'],[45,57,58,59,97]) && $post['birthday']>date('Y-m-d',strtotime('-14 year')) && $week==2){
            \Yii::$app->getSession()->setFlash('error','14周岁以下儿童预约HPV、乙肝等疫苗请在工作日周四上午的儿童门诊预约，儿童接种须携带接种本。');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        }

       


        if($post['doctorid']==38  && $post['birthday']<date('Y-m-d',strtotime('-14 year')) && $week==4){
            \Yii::$app->getSession()->setFlash('error','周四为儿童疫苗接种时间，成人请预约其他时间');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        }

        if(!preg_match("/^1[3456789]\d{9}$/", $post['phone'])){
            \Yii::$app->getSession()->setFlash('error','请填写正确手机号码');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        }

        if($post['vcode']!=110112 && $post['vaccine']!=64) {
            $isVerify = SmsSend::verifymessage($post['phone'], $post['vcode']);
            $isVerify = json_decode($isVerify, TRUE);
            if ($isVerify['code'] != 200) {
                \Yii::$app->getSession()->setFlash('error','手机验证码错误');
                return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
            }
        }
        if($post['vaccine']==80 && $post['birthday'] && $post['birthday']<date('Y-m-d',strtotime('-3 year'))) {
            \Yii::$app->getSession()->setFlash('error','此疫苗为三岁以儿童接种，超过三岁请勿预约！');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        }

        if(!$post['appoint_name'] || !$post['phone'] || !$post['sex']
            || !$post['doctorid'] || !$post['appoint_time'] || !$post['appoint_date'] || !$post['type']){
            \Yii::$app->getSession()->setFlash('error','提交失败，缺少参数');
            return $this->redirect(['wappoint/index']);
        }



        $doctor=UserDoctor::findOne(['userid'=>$post['doctorid']]);
        if($doctor){
            if(strpos($doctor->appoint,',')!==false){
                $types = explode(',',$doctor->appoint);
            }elseif ($doctor->appoint) {
                $types = str_split((string)$doctor->appoint);
            }
        }
        if(!$doctor || !$doctor->appoint || !in_array($post['type'],$types)){
            \Yii::$app->getSession()->setFlash('error','社区未开通');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        };
        $appoint = HospitalAppoint::findOne(['doctorid' => $post['doctorid'], 'type' => $post['type']]);



        //判断日期是否可约

        $weekv=[];

        $vid=$post['vaccine'];

        if($vid) {
            $weekv = HospitalAppointVaccine::find()
                ->select('week')
                ->where(['haid' => $appoint->id])
                ->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0], ['vaccine' => -1]])->groupBy('week')->column();
        }


        $hav = HospitalAppointVaccineDay::findOne(['haid'=>$appoint->id,'vaccine'=>$vid]);
        if($hav){
            $cycle = $hav->day;
        }else{
            $cycle = $appoint->cycle;
        }

        $is_appoint = $appoint->is_appoint($post['appoint_date'], $weekv,$cycle);
        if (!$is_appoint) {
            \Yii::$app->getSession()->setFlash('error','非线上预约门诊日，请选择其他日期！');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);


        }




        $w=date("w",$post['appoint_date']);
        $weeks = HospitalAppointWeek::find()
            ->andWhere(['week' => $w])
            ->andWhere(['haid' => $appoint->id])
            ->andWhere(['time_type'=>$post['appoint_time']])->one();


        $appointed = Appoint::find()
            ->andWhere(['type' => $post['type']])
            ->andWhere(['doctorid' => $post['doctorid']])
            ->andWhere(['appoint_date' =>$post['appoint_date']])
            ->andWhere(['appoint_time' => $post['appoint_time']])
            ->andWhere(['mode' => 0])
            ->andWhere(['<','state',3])
            ->count();

        $appointAdult=AppointAdult::findOne(['userid'=>$this->login->userid,'name'=>$post['appoint_name']]);
        $appointAdult=$appointAdult?$appointAdult:new AppointAdult();
        $appointAdult->userid=$this->login->userid;
        $appointAdult->name=$post['appoint_name'];
        $appointAdult->phone=$post['phone'];
        $appointAdult->gender=$post['sex'];
        $appointAdult->birthday=$post['birthday'];

        if($post['place']){
            $IdV=new IdcardValidator();
            $return=$IdV->idCardVerify($post['place']);
            if(!$return)
            {
                \Yii::$app->getSession()->setFlash('error','证件号验证失败');
                return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
            }
            $appointAdult->place=$post['place'];
        }
        if(!$appointAdult->save()){
            \Yii::$app->getSession()->setFlash('error','联系人信息保存失败');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);

        }

        if($post['vaccine']) {
            $week = date('w', $post['appoint_date']);

            $vaccine_count = Appoint::find()->where(['doctorid' => $post['doctorid'], 'vaccine' => $post['vaccine'], 'appoint_date' => $post['appoint_date']])->andWhere(['<', 'state', 3])->count();
            $hospitalAppointVaccineNum = HospitalAppointVaccineNum::findOne(['haid' => $appoint->id, 'week' => $week, 'vaccine' => $post['vaccine']]);
            if ($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num - $vaccine_count <= 0) {
                \Yii::$app->getSession()->setFlash('此疫苗' . date('Y年m月d日', $post['appoint_date']) . "已约满，请选择其他日期");
                return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
            }
        }

        if(($weeks->num-$appointed)<=0){
            \Yii::$app->getSession()->setFlash('error','该时间段已约满，请选择其他时间');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);

        }


        if(!in_array($post['vaccine'],[64,66,46])){
            //$appointA = Appoint::findOne(['phone' => $post['phone'], 'state' => [1,6,0], 'vaccine' => $post['vaccine']]);
        }
        $appointA = 0;
        if($appointA){
            \Yii::$app->getSession()->setFlash('error','您有未完成的预约');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);

        }elseif(!$appointAdult->userid){
            \Yii::$app->getSession()->setFlash('error','预约人联系信息保存失败');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);

        } else{
        

            $model = new Appoint();
            $post['state'] = 1;
            $post['userid'] = $this->login->userid;
            $post['loginid']=$this->login->id;
            $post['childid']=$appointAdult->id;
            $model->load(["Appoint" => $post]);
            if(!$this->login->phone){
                $this->login->phone=$post['phone'];
                $this->login->save();
            }
            if ($model->save()) {
                if($post['img']){
                    foreach($post['img'] as $k=>$v){
                        $appointImg=new AppointImg();
                        $appointImg->img=$v;
                        $appointImg->aid=$model->id;
                        $appointImg->save();
                    }
                }


    
                return $this->redirect(['wappoint/view','id'=>$model->id]);
            } else {
                \Yii::$app->getSession()->setFlash('error','提交失败');
                return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
            }
        }
    }
    public function actionTest(){
        return $this->render('test1');
    }
    public function actionUpload(){
        \Yii::$app->response->format=Response::FORMAT_JSON;

        $imagesFile = UploadedFile::getInstancesByName('img');

        if($imagesFile) {
                $upload= new UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
        }
        return ['code' => 200, 'src' =>$image];
    }
}