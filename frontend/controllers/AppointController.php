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
use common\components\Log;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use yii\base\Application;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use common\models\AppointCalling;
use common\models\AppointCallingList;
use common\models\queuing\Queue;

class AppointController extends Controller
{
    public $enableCsrfValidation = false;

    //public $layout='@frontend/views/layouts/h5.php';
    public $hs = [
        '9RV7H6Dv' => [248035],
        'tU459foO' => [248033],
        'a94PW3iX' => [206262],
        'KPW01H7g' => [113890],
        'E1mUGz95' => [192821],
        'ER8GOz85'=>[257888],
        'y479PewU'=>[314896],
        'YPYGI9lE'  =>590848,
        '91e43c3'=>89154,
        'b78e2b6'=>925348,
        'c04d4156'=>1511688,
        'ds843j32'=>1550005,
        'ebb7df6'=>206260,
    ];


    public function sign($h, $d, $s,$q='')
    {
        //echo md5($h.$q.date('Ymd')."rh6FcKyWOUqF52hf");exit;
        if ($s == md5($h .$q. date('Ymd') . "rh6FcKyWOUqF52hf")) {
            return true;
        }
        return false;
    }

    public function actionList($h, $d, $s)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $arr = [];

        $types=[
            1=>2,
            2=>1,
            4=>3,
        ];
        if ($this->sign($h, $d, $s) && $this->hs[$h]) {

            $appoint = Appoint::find()->where(['in', 'doctorid', $this->hs[$h]])
                ->andWhere(['appoint_date' => strtotime(date('Ymd'))])
                ->all();
            foreach ($appoint as $k => $v) {
                $rs['pat_Id'] = "appoint:" . $v->id;
                if($v->type<=2){
                    $child = \common\models\ChildInfo::findOne(['id' => $v->childid]);
                    $rs['pat_Name'] = $child->name;
                }else{
                    $appointAdult=AppointAdult::findOne(['userid'=>$v->userid]);
                    $rs['pat_Name'] =$appointAdult->name;
                }

                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $child->birthday));
                if ($DiffDate[0]) {
                    $age = $DiffDate[0] . "岁";
                } elseif ($DiffDate[1]) {
                    $age = $DiffDate[1] . "月";
                } else {
                    $age = $DiffDate[2] . "天";
                }
                $rs['age'] = $age;
                $rs['yuyueRiqi'] = date('Y-m-d');


                $index = \common\models\Appoint::find()
                    ->andWhere(['appoint_date' => $v->appoint_date])
                    ->andWhere(['<', 'id', $v->id])
                    ->andWhere(['doctorid' => $v->doctorid])
                    ->andWhere(['appoint_time' => $v->appoint_time])
                    ->andWhere(['type' => $v->type])
                    ->count();
                $n = $index + 1;
                $rs['seq_No'] = $n;

                switch ($v->doctorid) {
                    case 248033:
                        $quyuName = 1;
                        break;
                    case 234820:
                        $quyuName = 2;
                        break;
                    default:
                        $quyuName = 0;
                        break;
                }

                if(!$type=$types[$v->type]){
                    $type=$v->type;
                }

                $rs['dept_Name'] = $type;
                $rs['yuyueDate'] = Appoint::$timeText[$v->appoint_time];
                $rs['quyuName'] = $quyuName;
                $arr[] = $rs;
            }
        } else {
            $code = 20010;
            $msg = 'sign错误';
        }
        return ['code' => $code ? $code : 10000, 'msg' => $msg ? $msg : '成功', 'data' => $arr];
    }

    public function actionData($h, $d, $s)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $arr = [];

        if ($this->sign($h, $d, $s) && $this->hs[$h]) {
            $appoint = Appoint::find()->where(['doctorid'=>$this->hs[$h]])
                ->andWhere(['appoint_date' => strtotime(date('Ymd'))])
                ->all();
            foreach ($appoint as $k => $v) {
                $rs['qrcode'] = "appoint:" . $v->id;
                if($v->type<=2){
                    $child = \common\models\ChildInfo::findOne(['id' => $v->childid]);
                    $rs['name'] = $child->name;
                }else{
                    $appointAdult=AppointAdult::findOne(['userid'=>$v->userid]);
                    $rs['name'] =$appointAdult->name;
                }
                $rs['date'] = date('Y-m-d',$v->appoint_date);
                $rs['project'] = $v->type;
                $rs['time'] = Appoint::$timeText3[$v->appoint_time];
                $time = explode('-',$rs['time']);
                $rs['start'] = date('Y-m-d '.$time[0].':00');
                $rs['end'] = date('Y-m-d '.$time[1].':00');
                $rs['appointid'] = $v->id;
                $rs['userid'] = $v->userid;
                $arr[] = $rs;
                
            }
        } else {
            $code = 20010;
            $msg = 'sign错误';
        }
        return ['code' => $code ? $code : 10000, 'msg' => $msg ? $msg : '成功', 'data' => $arr,'project_t'=>Appoint::$typeText];
    }
    public function actionDoneNew($h, $d, $s,$q)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($this->sign($h, $d, $s,$q) && $this->hs[$h]) {

            $appid=explode(':',$q);
            if($id=intval($appid[1])){
                $appoint=Appoint::findOne($id);
                if($appoint){
                    $appoint->state=2;
                    $appoint->save();
                }else{
                    $code = 20010;
                    $msg = '参数错误';
                }
            }else{
                $code = 20010;
                $msg = '参数错误';
            }

        } else {
            $code = 20010;
            $msg = 'sign错误';
        }
        return ['code' => $code ? $code : 10000, 'msg' => $msg ? $msg : '成功'];
    }
    public function actionDel($doctorid){
        AppointCallingList::deleteAll(['doctorid' => $doctorid]);
        $redis=\Yii::$app->rd;
        $redis->del('Queue-'.$doctorid.'-'.date('Y-m-d').'-2-3');
        $redis->del('Queue-'.$doctorid.'-'.date('Y-m-d').'-2-');
        $redis->del('Queue-'.$doctorid.'-'.date('Y-m-d').'-2-s');

        $redis->del('Queue-'.$doctorid.'-'.date('Y-m-d').'-2-3s');

        $redis->del('Queue-'.$doctorid.'-'.date('Y-m-d').'-1-0');
        $redis->del('Queue-'.$doctorid.'-'.date('Y-m-d').'-4-0');

    }

    public function actionDone($h, $d, $s,$code=''){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $post=\Yii::$app->request->post();
        $log=new Log('appoint_done');
        $log->addLog($h);
        $log->saveLog();
        if ($this->sign($h, $d, $s) && $this->hs[$h]) {
            return ['code' => 10000, 'msg' => '成功','data'=>$post];

        }
        return ['code' => 20000, 'msg' => '请求失败','data'=>$post];
    }

    public function actionCalling($h, $d, $s,$aString,$type=0){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $appointArray=explode(':',$aString);
        $id=$appointArray[1];
        if($id == 'test'){
            return ['code' => 10000, 'msg' => '成功',
                'data' => [
                    'name' => '测试',
                    'type' => '测试',
                    'hospital' => '测试',
                    'num' => '测试',
                    'deng' => '测试',
                    'date' => date('Y年m月d日') . "测试",
            ]];
        }
        $doctorid=$this->hs[$h];
        $t=date('H:i');
        if($t>'16:00' || $t<'07:00'){
            return ['code' => 20000, 'msg' => '可使用时间为:<br>早7点至下午4点'];
        }

        $userDoctor=UserDoctor::findOne(['userid'=>$doctorid]);
        $fenzhen=$userDoctor->is_zhenshi;


        //计算时间段参数
        if($aString=='tmp') {
            $timeType=0;
            $aid=0;
        }else{
            $appoint = Appoint::findOne(['state'=>1,'doctorid'=>$doctorid,'id'=>$id,'appoint_date'=>strtotime(date('Y-m-d 00:00:00'))]);
            if($appoint) {
                $type = $appoint->type;
                if($type==1){
                    $type=2;
                    $typetpme=1;
                }else{
                    $typetpme=$type;
                }
                $aid = $appoint->id;
                $appointCallingListModel = AppointCallingList::findOne(['aid' => $appoint->id]);
                //判断用户是否已经排队
                if ($appointCallingListModel) {
                    if ($appointCallingListModel->state == 1) {
                        return ['code' => 20000, 'msg' => '已排队'];
                    } elseif ($appointCallingListModel->state == 3) {
                        return ['code' => 20000, 'msg' => '预约已完成'];
                    } elseif ($appointCallingListModel->state == 2) {
                        //过期重排
                        $timeType = Appoint::getTimeTypeTmp($doctorid, $type);
                        if (!$timeType) {
                            return ['code' => 20000, 'msg' => '可使用时间为:<br>早7点至下午4点'];
                        }

                        $appointCallingListModel->time = $timeType;
                        if ($appointCallingListModel->save()) {
                            $queue = new Queue($doctorid, $type, $timeType, $fenzhen);
                            $queueNum = $queue->lpush($appointCallingListModel->id);
                            $userDoctor = UserDoctor::findOne(['userid' => $appoint->doctorid]);
                            $hospital = Hospital::findOne(['id' => $userDoctor->hospitalid]);
                            return ['code' => 10000, 'msg' => '您的排队已过期，重新出号',
                                'data' => [
                                    'name' => $appoint->name(),
                                    'type' => Appoint::$typeText[$type],
                                    'hospital' => $hospital->name,
                                    'num' => $timeType . AppointCallingList::listName($appointCallingListModel->id, $doctorid, $type, $timeType),
                                    'deng' => ($queueNum - 1),
                                    'date' => date('Y年m月d日') . " " . Appoint::$timeText[$timeType]]];
                        } else {
                            return ['code' => 20000, 'msg' => '出号失败请重新尝试！'];
                        }
                    }

                } else {
                    $times = explode('-', Appoint::$timeText1[$appoint->appoint_time]);
                    $t=date('H:i');
                    if ($t < $times[1]) {
                        $timeType = $appoint->appoint_time;
                    } else {
                        $timeType = Appoint::getTimeTypeTmp($doctorid, $type);
                    }
                    //var_dump($timeType);exit;
                }
            }else{
                return ['code'=>30000,'msg'=>'未查询到预约信息'];
            }
        }
        $appointCallingListModel = new AppointCallingList();
        $appointCallingListModel->aid = $aid;
        $appointCallingListModel->openid = '';
        $appointCallingListModel->doctorid = $doctorid;
        $appointCallingListModel->time = $timeType;
        $appointCallingListModel->type = $type;
        if ($appointCallingListModel->save()) {
            $queue = new Queue($doctorid, $type, $timeType,$fenzhen);
            $queueNum = $queue->lpush($appointCallingListModel->id);
            $userDoctor=UserDoctor::findOne(['userid'=>$doctorid]);
            $hospital=Hospital::findOne(['id'=>$userDoctor->hospitalid]);
            if($timeType==0) {
                return ['code' => 10000, 'msg' => '成功',
                    'data' => [
                        'name' => '临时',
                        'type' => Appoint::$typeText[$typetpme],
                        'hospital' => $hospital->name,
                        'num' => '临时' . AppointCallingList::listName($appointCallingListModel->id, $doctorid, $type, 0),
                        'deng' => "(临时号排在预约号之后)",
                        'date' => date('Y年m月d日') . " 临时"]];
            }else {
                return ['code' => 10000, 'msg' => '成功',
                    'data' => [
                        'name' => $appoint->name(),
                        'type' => Appoint::$typeText[$typetpme],
                        'hospital' => $hospital->name,
                        'num' => $timeType . AppointCallingList::listName($appointCallingListModel->id, $doctorid, $type, $timeType),
                        'deng' => $timeType . AppointCallingList::listName($appointCallingListModel->id, $doctorid, $type, $timeType),
                        'date' => date('Y年m月d日') . " " . Appoint::$timeText[$timeType]]];
            }
        }else{
            return ['code'=>30000,'msg'=>'失败',$doctorid,$appointCallingListModel->firstErrors];
        }
    }
}