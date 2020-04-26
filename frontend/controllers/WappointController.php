<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;

use api\models\ChildInfo;
use common\components\Code;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use EasyWeChat\Factory;
use yii\web\Response;


class WappointController extends Controller
{

    public function actionIndex($search = '', $county = 0)
    {

        $hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['in','userid',$hospitalAppoint]);
        if ($search) {
            $query->andFilterWhere(['like', 'name', $search]);
        }
        if ($search || $county) {

            if ($county) {
                $query->andWhere(['county' => $county]);
            }

            $doctors = $query->orderBy('appoint desc')->all();
        } else {
            $doctors = $query->orderBy('appoint desc')->limit(50)->all();
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
            }

            $docs[] = $rs;
        }


        return $this->render('list', [
            'doctors' => $docs,
            'county' => $county,
            'doctorid'=>$doctorid
        ]);
    }

    public function actionFrom($userid)
    {
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
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $userid, 'type' => 4]);

        $days=[];
        $weekr = str_split((string)$hospitalA->weeks);
        $cycleType = [0, 7, 14, 30];
        $cycle = $cycleType[$hospitalA->cycle];
        $delay = $hospitalA->delay;
        $day = strtotime(date('Y-m-d',strtotime('+' . $delay . " day")));

        for ($i = 1; $i <= $cycle; $i++) {
            $day = $day + 86400;
            $rs['time'] = $day;
            $rs['date']=$rs['time'];
            $rs['day'] = date('d', $rs['time']);
            $rs['week'] = date('w', $rs['time']);
            if (!in_array(date('Y-m-d', $rs['time']), $holiday) && in_array($rs['week'], $weekr)) {
                $days[] = $rs;
            }
        }

        if($days){
            $firstDay = $days[0]['date'];
        }else{
            $firstDay = $day + 86400;
        }
        $doctor=UserDoctor::findOne(['userid'=>$userid]);
        if($doctor){
            $doctorRow=$doctor->toArray();
            $doctorRow['hospital']=Hospital::findOne($doctor->hospitalid)->name;
        }

        $appointAdult=AppointAdult::findOne(['userid'=>$this->login->userid]);


        return $this->render('from', ['days' => $days,'day'=>$firstDay,'doctor'=>$doctorRow,'user'=>$appointAdult]);
    }
    public function actionDayNum($doctorid, $day)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $rs = [];
        $times=[];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => 4]);
        $week=date('w',strtotime($day));

        $weeks = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $hospitalA->id])->orderBy('time_type asc')->all();
        if ($weeks) {
            $appoints = Appoint::find()
                ->select('count(*)')
                ->andWhere(['type' => 4])
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
                ->andWhere(['type' => 4])
                ->andWhere(['doctorid' => $doctorid])
                ->andWhere(['appoint_date' => strtotime($day)])
                ->andWhere(['!=', 'state', 3])
                ->andWhere(['mode' => 0])
                ->orderBy('createtime desc')
                ->one();
            if($firstAppoint){
                foreach($rs as $k=>$v){
                    if($firstAppoint->appoint_time>6 && $k>6){
                        $times[$k]=$v;
                    }
                    if($firstAppoint->appoint_time<7 && $k<7){
                        $times[$k]=$v;
                    }
                }
            }else{
                foreach($rs as $k=>$v){
                    if($hospitalA->interval==2 && $k>6){
                        $times[$k]=$v;
                    }
                    if($hospitalA->interval==1 && $k<7){
                        $times[$k]=$v;
                    }
                }
            }

        }
        return ['times'=>$times];
    }
    /**
     * 发送验证码
     * @param $phone
     * @return Code
     */
    public function actionCode($phone){
        \Yii::$app->response->format=Response::FORMAT_JSON;

        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
            return ['code'=>20010,'msg'=>'手机号码格式错误'];
        }
        $sendData=SmsSend::sendSms($phone,'SMS_150575871');
        return ['code'=>10000,'msg'=>'成功'];

    }
    public function actionVphone($phone,$vcode){
        \Yii::$app->response->format=Response::FORMAT_JSON;

        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
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

        $row=$appoint->toArray();
        $doctor=UserDoctor::findOne(['userid'=>$appoint->doctorid]);
        if($doctor){
            $hospital=Hospital::findOne($doctor->hospitalid);
        }
        $row['hospital']=$hospital->name;
        $row['type']=Appoint::$typeText[$appoint->type];
        $row['time']=date('Y.m.d',$appoint->appoint_date)."  ".Appoint::$timeText[$appoint->appoint_time];
        $row['child_name']=AppointAdult::findOne($appoint->userid)->name;
        $row['duan']=$appoint->appoint_time;

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
        $appoints = Appoint::findAll(['userid' => $this->login->userid,'type'=>4,'state'=>$type]);
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
            $row['child_name']=AppointAdult::findOne(['userid'=>$v->userid])->name;
            $list[]=$row;
        }

        return $this->render('my',['list'=>$list,'type'=>$type]);
    }

    public function actionState($id,$type){
        $model=Appoint::findOne(['id'=>$id,'userid'=>$this->login->userid]);
        if(!$model){
            \Yii::$app->getSession()->setFlash('error','失败');
            return $this->redirect(['wappoint/my']);
        }else{

            if($type==1){
                $model->state=3;
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

        if(!$post['appoint_name'] || !$post['phone'] || !$post['sex']
            || !$post['doctorid'] || !$post['appoint_time'] || !$post['appoint_date'] || !$post['type']){
            \Yii::$app->getSession()->setFlash('error','提交失败，缺少参数');
            return $this->redirect(['wappoint/index']);
        }

        $doctor=UserDoctor::findOne(['userid'=>$post['doctorid']]);
        if($doctor){
            $hospital=Hospital::findOne($doctor->hospitalid);
            if($doctor->appoint){
                $types=str_split((string)$doctor->appoint);
            }
        }
        if(!$doctor || !$doctor->appoint || !in_array($post['type'],$types)){
            \Yii::$app->getSession()->setFlash('error','社区未开通');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
        };
        $appoint = HospitalAppoint::findOne(['doctorid' => $post['doctorid'], 'type' => $post['type']]);

        $w=date("w",$post['appoint_date']);
        $weeks = HospitalAppointWeek::find()
            ->andWhere(['week' => $w])
            ->andWhere(['haid' => $appoint->id])
            ->andWhere(['time_type'=>$post['appoint_time']])->one();


        $appointed = Appoint::find()
            ->andWhere(['type' => $post['type']])
            ->andWhere(['doctorid' => $post['doctorid']])
            ->andWhere(['appoint_date' => strtotime($post['appoint_date'])])
            ->andWhere(['appoint_time' => $post['appoint_time']])
            ->andWhere(['mode' => 0])
            ->andWhere(['<','state',3])
            ->count();


        if(($weeks->num-$appointed)<=0){
            \Yii::$app->getSession()->setFlash('error','该时间段已约满，请选择其他时间');
            return $this->redirect(['wappoint/from','id'=>$post['doctorid']]);

        }

        $appointAdult=AppointAdult::findOne(['userid'=>$this->login->userid]);
        $appointAdult=$appointAdult?$appointAdult:new AppointAdult();
        $appointAdult->userid=$this->login->userid;
        $appointAdult->name=$post['appoint_name'];
        $appointAdult->phone=$post['phone'];
        $appointAdult->gender=$post['sex'];
        //$appointAdult->birthday=strtotime($post['birthday']);
        if(!$appointAdult->save()){
            \Yii::$app->getSession()->setFlash('error','联系人信息保存失败');
            return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);

        }


        $appoint=Appoint::findOne(['userid'=>$appointAdult->userid,'type'=>$post['type'],'state'=>1]);
        if($appoint){
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
            $model->load(["Appoint" => $post]);
            if(!$this->login->phone){
                $this->login->phone=$post['phone'];
                $this->login->save();
            }
            if ($model->save()) {
                return $this->redirect(['wappoint/view','id'=>$model->id]);
            } else {
                \Yii::$app->getSession()->setFlash('error','提交失败');
                return $this->redirect(['wappoint/from','userid'=>$post['doctorid']]);
            }
        }
    }
}