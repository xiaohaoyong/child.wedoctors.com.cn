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
use common\models\AppointExpert;

use common\models\AppointAdult;
use common\models\AppointOrder;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointExpert;
use common\models\HospitalAppointExpertNum;
use common\models\HospitalAppointVaccineTimeNum;

use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use common\models\Vaccine;
use EasyWeChat\Factory;
use yii\web\Response;


class ZappointController extends Controller
{

    public function actionIndex($search = '', $county = 0)
    {

        //$hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['like', 'appoint', 13]);
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
        $doctorParent = DoctorParent::findOne(['parentid' => $this->login->userid]);
        if ($doctorParent) {
            $doctorid = $doctorParent->doctorid;
        }

        foreach ($doctors as $k => $v) {
            $rs = $v->toArray();
            $rs['name'] = Hospital::findOne($v->hospitalid)->name;
            $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $v->userid, 'type' => 13]);
            if ($hospitalAppoint) {
                $week = str_replace([1, 2, 3, 4, 5, 6, 0], ['一', '二', '三', '四', '五', '六', '七'], $hospitalAppoint->weeks);
                $rs['week'] = preg_split('/(?<!^)(?!$)/u', $week);
                $rs['phone'] = $hospitalAppoint->phone;
                $rs['appoint_intro'] = $hospitalAppoint->info;
            }
            $docs[] = $rs;
        }


        return $this->render('index', [
            'doctors' => $docs,
            'county' => $county,
            'doctorid' => $doctorid
        ]);
    }

    public function actionFrom($userid, $vid = 0)
    {
        $appoint = new Appoint();
        $appoint->scenario = 'z';
        $appoint->vaccine = $vid;

        $appointAdult = AppointAdult::findOne(['userid' => $this->login->userid]);
        $appointAdult = $appointAdult ? $appointAdult : new AppointAdult();
        $appointAdult->scenario = 'z';
        $appointAdult->userid = $this->login->userid;
        $doctor = UserDoctor::findOne(['userid' => $userid]);
        if ($doctor) {
            $doctorRow = $doctor->toArray();
            $doctorRow['hospital'] = Hospital::findOne($doctor->hospitalid)->name;
        }
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $userid, 'type' => 13]);

        if ($post = \Yii::$app->request->post()) {

            $appoint_log = Appoint::find()->where(['userid' => $this->login->userid, 'type' => 13])->andWhere(['<', 'state', 3])->one();
            if ($appoint_log) {
                \Yii::$app->getSession()->setFlash('error', '您有未完成的预约！');
                return $this->redirect(['zappoint/from', 'userid' => $appoint->doctorid]);

            } else {

                if ($appointAdult->load($post) && $appointAdult->validate()) {
                    if ($appointAdult->save()) {
                        $appoint->state = 1;
                        $appoint->userid = $this->login->userid;
                        $appoint->loginid = $this->login->id;
                        $appoint->childid = $appointAdult->id;
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
                                return $this->redirect(['zappoint/from', 'userid' => $appoint->doctorid]);
                            };
                            $hospitalA = HospitalAppoint::findOne(['doctorid' => $appoint->doctorid, 'type' => $appoint->type]);




                            $week = date('w', $appoint->appoint_date);

                            $vaccine_count = Appoint::find()->where(['doctorid' => $userid, 'vaccine' => $appoint->vaccine, 'appoint_date' => $appoint->appoint_date,'appoint_time'=>$appoint->appoint_time,'type'=>13])->andWhere(['<', 'state', 3])->count();
                            $hospitalAppointVaccineNum = HospitalAppointVaccineTimeNum::findOne(['type'=>13, 'week' => $week, 'vaccine' => $appoint->vaccine,'appoint_time'=>$appoint->appoint_time]);
                            

                            if ($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num - $vaccine_count <= 0) {
                                \Yii::$app->getSession()->setFlash('error',"此专家已约满，请选择其他日期时段");

                                return $this->redirect(['zappoint/from','userid'=>$userid]);
                            }


                            $appointb = Appoint::find()->where(['userid' => $appointAdult->userid, 'type' => $appoint->type])->andWhere(['state' => 2])->orderBy('id desc')->one();
                            if ($appointb->state == 1) {
                                \Yii::$app->getSession()->setFlash('error', '您有未完成的预约');
                                return $this->redirect(['zappoint/from', 'userid' => $userid]);
                            }
                        }
                        if ($appoint->save()) {
                            return $this->redirect(['zappoint/view', 'id' => $appoint->id]);
                        } else {
                            \Yii::$app->getSession()->setFlash('error', $appoint->firstErrors);
                            return $this->redirect(['zappoint/from', 'userid' => $userid]);
                        }
                    } else {

                        \Yii::$app->getSession()->setFlash('error', '提交失败1');
                        return $this->redirect(['zappoint/from', 'userid' => $userid]);
                    }
                } else {
                    \Yii::$app->getSession()->setFlash('error', $appointAdult->firstErrors);
                    return $this->redirect(['zappoint/from', 'userid' =>$userid]);
                }
            }
        }

        //判断所选疫苗都有周几可约
        if ($vid) {
            $expert = AppointExpert::findOne($vid);
            if ($expert) {
                $query = HospitalAppointExpert::find()
                    ->select('week')
                    ->where(['haid' => $hospitalA->id])
                    ->andWhere(['expert' => $vid]);
                $vaccineWeek = $query->groupBy('week')->column();
                //如该疫苗无法获取周几可约则视为非法访问
                if (!$vaccineWeek) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;

                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }

        $days = [];

        if ($vid) {
            $weekr = $vaccineWeek ? $vaccineWeek : str_split((string)$hospitalA->weeks);
            $cycleType = [0, 7, 14, 30];
            $cycle = HospitalAppoint::$cycleNum[$hospitalA->cycle];
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
                if ($hospitalA->is_appoint($day, $weekr)) {
                    $days[] = $rs;
                }
            }
            if ($days) {
                $firstDay = $days[0]['date'];
            } else {
                $firstDay = $day + 86400;
            }
        }
        $experts = AppointExpert::find()->select('name')->indexBy('id')->where(['doctorid' => $userid])->column();


        return $this->render('from', ['day' => $firstDay, 'days' => $days, 'appoint' => $appoint, 'doctor' => $doctorRow, 'user' => $appointAdult, 'experts' => $experts, 'vid' => $vid, 'firstday' => $days[0]['date']]);
    }
    public function actionExpert($e)
    {

        $expert = AppointExpert::findOne($e);
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return ['view' => $expert->info];
    }
    public function actionDayNum($doctorid, $week = 0, $type = 13, $day, $vid = 0, $sid = 0)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $rs = [];
        $times = [];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);

        $weekv = [];
        if ($vid) {
            $weekv = HospitalAppointExpert::find()
                ->select('week')
                ->where(['haid' => $hospitalA->id])
                ->andWhere(['expert' => $vid])->groupBy('week')->column();
        }

        if ($weekv && $streetWeek) {
            $weekr = array_intersect($weekv, $streetWeek);
        } elseif ($streetWeek || $weekv) {
            $weekr = $streetWeek ? $streetWeek : $weekv;
        }


        $is_appoint = $hospitalA->is_appoint(strtotime($day), $weekr);
        if (!$is_appoint) {
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => '非线上预约门诊日，请选择其他日期！'];
        }
        if ($is_appoint == 2) {
            $d = HospitalAppoint::$cycleNum[$hospitalA->cycle] + $hospitalA->delay;
            $date = date('Y年m月d日', strtotime('-' . $d . ' day', strtotime($day)));
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => "放号时间：" . $date . " " . HospitalAppoint::$rtText[$hospitalA->release_time]];
        }
        $week = date('w', strtotime($day));

        $vaccine_count = Appoint::find()->where(['vaccine' => $vid, 'appoint_date' => strtotime($day), 'doctorid' => $doctorid])->andWhere(['<', 'state', 3])->count();
        $hospitalAppointExpertNum = HospitalAppointVaccineTimeNum::find()->where(['type' => $hospitalA->type, 'week' => $week,'vaccine'=>$vid,'doctorid'=>$doctorid])->sum('num');
        if ($hospitalAppointExpertNum && $hospitalAppointExpertNum - $vaccine_count <= 0) {
            return ['list' => [], 'is_appoint' => 0, 'text' => '此科室' . date('Y年m月d日', strtotime($day)) . "已约满，请选择其他日期"];
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

        if ($firstAppoint) {
            if ($firstAppoint->appoint_time < 7) {
                $wquery->andWhere(['<', 'time_type', 7]);
            }
            if ($firstAppoint->appoint_time > 6) {
                $wquery->andWhere(['>', 'time_type', 6]);
            }
        } else {
            if ($hospitalA->interval == 1) {
                $wquery->andWhere(['<', 'time_type', 7]);
            }
            if ($hospitalA->interval == 2) {
                $wquery->andWhere(['>', 'time_type', 6]);
            }
        }
        $weeks = $wquery->all();

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
                unset($rs[19]);
                unset($rs[20]);
            }



            //判断所选疫苗是否按照时间段分配
            if ($vid) {
                $hospitalAppointVaccineTimeNum = HospitalAppointVaccineTimeNum::find()
                    ->where(['vaccine' => $vid, 'week' => $week, 'type' => $type, 'doctorid' => $doctorid])
                    ->select('num')
                    ->indexBy('appoint_time')
                    ->column();
                if ($hospitalAppointVaccineTimeNum) {
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

                $num = $v;

                if ($hospitalAppointVaccineTimeNum) {
                    if ($hospitalAppointVaccineTimeNum[$k]) {
                        if ($v >= ($hospitalAppointVaccineTimeNum[$k] - $appointVaccineNum[$k])) {
                            $num = ($hospitalAppointVaccineTimeNum[$k] - $appointVaccineNum[$k]);
                        }
                    } else {
                        $num = 0;
                    }
                    $num1 = $hospitalAppointVaccineTimeNum[$k];
                } else {
                    $num1 = $rs_num[$k];
                }
                $rows['time'] = Appoint::$timeText[$k];
                $rows['appoint_time'] = $k;
                $rows['num'] = $num;
                $rows['num1'] = $num1;
                $times[] = $rows;
            }
            return ['list' => $times, 'is_appoint' => $is_appoint, 'text' => ''];
        }
        return new Code(20020, '未设置');
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
        $row['child_name'] = AppointAdult::findOne(['userid' => $appoint->userid])->name;
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
        $appoints = Appoint::findAll(['userid' => $this->login->userid, 'type' => 13, 'state' => $type]);
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
            return $this->redirect(['zappoint/my']);
        } else {

            if ($type == 1) {
                $model->state = 3;
            } elseif ($type == 2) {
                $model->state = 1;
            }

            if (!$model->save()) {
                \Yii::$app->getSession()->setFlash('error', '失败');
            }
            return $this->redirect(['zappoint/my']);
        }
    }

    public function actionList()
    {

        return $this->render('list');
    }
}
