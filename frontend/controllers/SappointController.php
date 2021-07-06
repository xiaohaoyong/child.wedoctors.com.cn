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
use common\models\AppointOrder;
use common\models\AppointOrder2;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use common\models\Vaccine;
use EasyWeChat\Factory;
use yii\web\Response;


class SappointController extends Controller
{

    public $type = 11;

    public function actionIndex($search = '', $county = 0)
    {

        //$hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['like','appoint',$this->type]);
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
            $hospitalAppoint=HospitalAppoint::findOne(['doctorid'=>$v->userid,'type'=>$this->type]);
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


        return $this->render('index', [
            'doctors' => $docs,
            'county' => $county,
            'doctorid'=>$doctorid
        ]);
    }

    public function actionFrom($userid, $vid = 0)
    {
        $dateMsg = ['不可约', '可约', '未放号'];

        $appoint = new Appoint();
        $appointAdult = AppointAdult::findOne(['userid' => $this->login->userid]);
        $appointAdult = $appointAdult ? $appointAdult : new AppointAdult();
        $appointAdult->scenario = 's';
        $appointAdult->userid = $this->login->userid;
        $doctor = UserDoctor::findOne(['userid' => $userid]);

        $appointOrder = AppointOrder2::findOne(['aoid' => $appointAdult->id]);
        $appointOrder = $appointOrder ? $appointOrder : new AppointOrder2();

        if ($doctor) {
            $doctorRow = $doctor->toArray();
            $doctorRow['hospital'] = Hospital::findOne($doctor->hospitalid)->name;
        }
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $userid, 'type' => $this->type]);

        if ($post = \Yii::$app->request->post()) {

            $appoint_log = Appoint::find()->where(['userid' => $this->login->userid, 'type' => $this->type])->andWhere(['<', 'state', 3])->one();
            if ($appoint_log) {
                \Yii::$app->getSession()->setFlash('error', '您有未完成的预约！');
            } else {

                if ($appointAdult->load($post) && $appointAdult->save()) {

                    $appointOrder->aoid=$appointAdult->id;
                    if($appointOrder->load($post)  && $appointOrder->save()){
                        $appoint->state = 1;
                        $appoint->userid = $this->login->userid;
                        $appoint->loginid = $this->login->id;
                        if ($appoint->load($post) && $appoint->validate()) {
                            if ($doctor) {
                                if (strpos($doctor->appoint, ',') !== false) {
                                    $types = explode(',', $doctor->appoint);
                                } elseif ($doctor->appoint) {
                                    $types = str_split((string)$doctor->appoint);
                                }
                            }
                            if (!$doctor || !$doctor->appoint || !in_array($appoint->type, $types)) {
                                \Yii::$app->getSession()->setFlash('error', '社区暂未开通');
                                return $this->redirect(['sappoint/from', 'userid' => $appoint->doctorid]);
                            };
                            $hospitalA = HospitalAppoint::findOne(['doctorid' => $appoint->doctorid, 'type' => $appoint->type]);

                            $w = date("w", $appoint->appoint_date);
                            $weeks = HospitalAppointWeek::find()
                                ->andWhere(['week' => $w])
                                ->andWhere(['haid' => $hospitalA->id])
                                ->andWhere(['time_type' => $appoint->appoint_time])->one();


                            $appointed = Appoint::find()
                                ->andWhere(['type' => $appoint->type])
                                ->andWhere(['doctorid' => $appoint->doctorid])
                                ->andWhere(['appoint_date' => $appoint->appoint_date])
                                ->andWhere(['appoint_time' => $appoint->appoint_time])
                                ->andWhere(['mode' => 0])
                                ->andWhere(['<', 'state', 3])
                                ->count();

                            if (($weeks->num - $appointed) <= 0) {
                                \Yii::$app->getSession()->setFlash('error', '该时间段已约满，请选择其他时间');
                                return $this->redirect(['sappoint/from', 'userid' => $appoint->doctorid]);

                            }


                            $appointb = Appoint::find()->where(['userid' => $appointAdult->userid, 'type' => $appoint->type])->andWhere(['state' => 2])->orderBy('id desc')->one();
                            if ($appointb->state == 1) {
                                \Yii::$app->getSession()->setFlash('error', '您有未完成的预约');
                                return $this->redirect(['sappoint/from', 'userid' => $appoint->doctorid]);
                            }

                            //三年内禁止预约
                            if ($appoint->save()) {
                                return $this->redirect(['sappoint/view', 'id' => $appoint->id]);
                            } else {
                                \Yii::$app->getSession()->setFlash('error', '提交失败');
                                return $this->redirect(['sappoint/from', 'userid' => $post['doctorid']]);
                            }
                        }else{
                            \Yii::$app->getSession()->setFlash('error',implode(',',$appoint->firstErrors));
                            return $this->redirect(['sappoint/from','userid'=>$post['doctorid']]);
                        }
                    }else{
                        \Yii::$app->getSession()->setFlash('error',implode(',',$appointOrder->firstErrors));
                        return $this->redirect(['sappoint/from','userid'=>$post['doctorid']]);
                    }

                }else{
                    \Yii::$app->getSession()->setFlash('error',implode(',',$appointAdult->firstErrors));
                    return $this->redirect(['sappoint/from','userid'=>$post['doctorid']]);
                }
            }
        }


        $days = [];
        $weekr = str_split((string)$hospitalA->weeks);
        $cycleType = [0, 7, 14, 30];
        $cycle = $cycleType[$hospitalA->cycle];
        $delay = $hospitalA->delay;
        $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));
        $dweek = ['日', '一', '二', '三', '四', '五', '六'];
        for ($i = 1; $i <= $cycle; $i++) {
            $day = $day + 86400;
            $rs['date'] = $day;
            $rs['day'] = date('m.d', $day);
            $rs['dateStr'] = date('Ymd', $day);
            $rs['week'] = date('w', $day);
            $rs['weekStr'] = $dweek[$rs['week']];
            $rs['dateState'] = $hospitalA->is_appoint($day, $weekr);
            $rs['dateMsg'] = $dateMsg[$rs['dateState']];

            if ($hospitalA->is_appoint($day, $weekr)) {
                $days[] = $rs;
            }

        }
        if ($days) {
            $firstDay = $days[0]['date'];
        } else {
            $firstDay = $day + 86400;
        }

        return $this->render('from', ['firstday' => $firstDay, 'days' => $days, 'appoint' => $appoint, 'doctor' => $doctorRow, 'user' => $appointAdult, 'appointOrder' => $appointOrder]);
    }

    public function actionDayNum($doctorid, $day)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $rs = [];
        $times = [];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $this->type]);
        $week = date('w', strtotime($day));

        $weeks = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $hospitalA->id])->andWhere(['<', 'time_type', '19'])->orderBy('time_type asc')->all();
        if ($weeks) {
            $appoints = Appoint::find()
                ->select('count(*)')
                ->andWhere(['type' => $this->type])
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
            $firstAppoint = Appoint::find()
                ->andWhere(['type' => $this->type])
                ->andWhere(['doctorid' => $doctorid])
                ->andWhere(['appoint_date' => strtotime($day)])
                ->andWhere(['!=', 'state', 3])
                ->andWhere(['mode' => 0])
                ->orderBy('createtime desc')
                ->one();
            if ($firstAppoint) {
                foreach ($rs as $k => $v) {
                    if ($firstAppoint->appoint_time > 6 && $k > 6) {
                        $times[$k] = $v;
                    }
                    if ($firstAppoint->appoint_time < 7 && $k < 7) {
                        $times[$k] = $v;
                    }
                }
            } else {
                foreach ($rs as $k => $v) {
                    if ($hospitalA->interval == 2 && $k > 6) {
                        $times[$k] = $v;
                    }
                    if ($hospitalA->interval == 1 && $k < 7) {
                        $times[$k] = $v;
                    }
                }
            }

        }
        return ['times' => $times];
    }

    public function actionView($id)
    {

        $appoint = Appoint::findOne(['id' => $id]);

        $row = $appoint->toArray();
        $doctor = UserDoctor::findOne(['userid' => $appoint->doctorid]);
        if ($doctor) {
            $hospital = Hospital::findOne($doctor->hospitalid);
        }
        $row['hospital'] = $hospital->name;
        $row['type'] = Appoint::$typeText[$appoint->type];
        $row['time'] = date('Y.m.d', $appoint->appoint_date) . "  " . Appoint::$timeText[$appoint->appoint_time];
        $row['child_name'] = $appoint->name();
        $row['duan'] = $appoint->appoint_time;
        if ($appoint->vaccine == -2) {
            $row['vaccineStr'] = '两癌筛查';
        } else {
            $vaccine = Vaccine::findOne($appoint->vaccine);
            $row['vaccineStr'] = $vaccine ? $vaccine->name : '';
        }
        $index = Appoint::find()
            ->andWhere(['appoint_date' => $appoint->appoint_date])
            ->andWhere(['<', 'id', $id])
            ->andWhere(['doctorid' => $appoint->doctorid])
            ->andWhere(['appoint_time' => $appoint->appoint_time])
            ->andWhere(['type' => $appoint->type])
            ->count();
        $row['index'] = $index + 1;

        return $this->render('view', ['row' => $row]);

    }

    public function actionMy($type = 1)
    {
        $appoints = Appoint::findAll(['userid' => $this->login->userid, 'type' => $this->type, 'state' => $type]);
        $list = [];
        foreach ($appoints as $k => $v) {
            $row = $v->toArray();
            $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
            if ($doctor) {
                $hospital = Hospital::findOne($doctor->hospitalid);
            }
            $row['hospital'] = $hospital->name;
            $row['type'] = Appoint::$typeText[$v->type];
            $row['time'] = date('Y.m.d', $v->appoint_date) . "  " . Appoint::$timeText[$v->appoint_time];
            $row['stateText'] = Appoint::$stateText[$v->state];
            $row['child_name'] = AppointAdult::findOne(['userid' => $v->userid])->name;
            $list[] = $row;
        }

        return $this->render('my', ['list' => $list, 'type' => $type, 'userid' => $this->login->userid]);
    }

    public function actionState($id, $type)
    {
        $model = Appoint::findOne(['id' => $id, 'userid' => $this->login->userid]);
        if (!$model) {
            \Yii::$app->getSession()->setFlash('error', '失败');
            return $this->redirect(['sappoint/my']);
        } else {

            if ($type == 1) {
                $model->state = 3;
            } elseif ($type == 2) {
                $model->state = 1;
            }

            if (!$model->save()) {
                \Yii::$app->getSession()->setFlash('error', '失败');
            }
            return $this->redirect(['sappoint/my']);
        }
    }

    public function actionList()
    {

        return $this->render('list');
    }

}