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
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use yii\web\Response;


class WappointController extends \frontend\controllers\Controller
{

    public function actionIndex($search = '', $county = 0)
    {
        $query = UserDoctor::find();
        if ($search) {
            $query->andFilterWhere(['like', 'name', $search]);
        }
        if ($search || $county) {

            if ($county) {
                $query->andWhere(['county' => $county]);
            }

            $doctors = $query->orderBy('appoint desc')->all();
        } else {
            $doctors = $query->orderBy('appoint desc')->limit(10)->all();
        }

        $docs = [];

        foreach ($doctors as $k => $v) {
            $rs = $v->toArray();
            $rs['name'] = Hospital::findOne($v->hospitalid)->name;
            $docs[] = $rs;
        }

        return $this->render('list', [
            'doctors' => $docs,
            'county' => $county
        ]);
    }

    public function actionView($userid)
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


        return $this->render('view', ['days' => $days,'day'=>$firstDay,'doctor'=>$doctorRow]);
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


    public function actionSave(){
        $post=\Yii::$app->request->post();

        $doctor=UserDoctor::findOne(['userid'=>$post['doctorid']]);
        if($doctor){
            $hospital=Hospital::findOne($doctor->hospitalid);
            if($doctor->appoint){
                $types=str_split((string)$doctor->appoint);
            }
        }
        if(!$doctor || !$doctor->appoint || !in_array($post['type'],$types)){
            return new Code(21000, '社区未开通');
        };
        $appoint = HospitalAppoint::findOne(['doctorid' => $post['doctorid'], 'type' => $post['type']]);

        $w=date("w",strtotime($post['appoint_date']));
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
            return new Code(21000,'该时间段已约满，请选择其他时间');
        }




        $appoint=Appoint::findOne(['childid'=>$post['childid'],'type'=>$post['type'],'state'=>1]);
        if($appoint){
            return new Code(20020,'您有未完成的预约');
        }elseif(!$post['childid']){
            return new Code(20020,'请选择宝宝');
        } else{

            $model = new Appoint();
            $post['appoint_date'] = strtotime($post['appoint_date']);
            $post['state'] = 1;
            $post['userid'] = $this->userid;
            $post['loginid']=$this->userLogin->id;
            $model->load(["Appoint" => $post]);



            if ($model->save()) {
                //var_dump($doctor->name);
                $userLogin=$this->userLogin;
                if($userLogin->openid) {

                    $child=ChildInfo::findOne($model->childid);

                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => $hospital->name),
                        'keyword3' => ARRAY('value' => date('Y-m-d',$model->appoint_date)." ".Appoint::$timeText[$model->appoint_time]),
                        'keyword4' => ARRAY('value' => $child?$child->name:''),
                        'keyword5' => ARRAY('value' => $model->phone),
                        'keyword6' => ARRAY('value' => "预约成功"),
                        'keyword7' => ARRAY('value' => $model->createtime),
                        'keyword8' => ARRAY('value' => Appoint::$typeInfoText[$model->type]),
                    ];
                    $rs = WechatSendTmp::send($data,$userLogin->openid, 'Ejdm_Ih_W0Dyi6XrEV8Afrsg6HILZh0w8zg2uF0aIS0', '/pages/appoint/view?id='.$model->id,$post['formid']);
                }

                return ['id' => $model->id];
            } else {
                return new Code(20010, implode(':', $model->firstErrors));
            }
        }

    }
}