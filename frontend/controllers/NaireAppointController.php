<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:56
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\UploadForm;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointStreet;
use common\models\QuestionNaire;
use common\models\QuestionNaireAnswer;
use common\models\QuestionNaireAsk;
use common\models\QuestionNaireField;
use common\models\Street;
use common\models\UserDoctor;
use OSS\OssClient;
use yii\web\Response;

use api\models\ChildInfo;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\DoctorParent;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointVaccineTimeNum;
use common\models\HospitalAppointWeek;
use common\models\Vaccine;
use EasyWeChat\Factory;
use yii\widgets\ActiveForm;

class NaireAppointController extends Controller
{
    public function actionIndex($search = '', $county = 0)
    {

        //$hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['like','appoint',9]);
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
            $hospitalAppoint=HospitalAppoint::findOne(['doctorid'=>$v->userid,'type'=>9]);
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
            'doctorid'=>$doctorid
        ]);
    }
    public function actionForm($id, $doctorid = 0)
    {

        $qnaa = QuestionNaireField::find()->where(['qnid' => $id, 'userid' => $this->login->userid,'state'=>1])->orderBy('id desc')->one();
        if ($qnaa && strtotime('+1 day', $qnaa->createtime) > time()) {
            return $this->redirect(['question-naire/healthy', 'id' => $id]);
        }
        $qnaa = new QuestionNaireAnswer();
        $qn = QuestionNaire::findOne($id);
        $qna = QuestionNaireAsk::findAll(['qnid' => $id]);
        $post = \Yii::$app->request->post()['QuestionNaireAnswer'];
        if (\Yii::$app->request->isAjax && $qnaa->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $reutnr= ActiveForm::validate($qnaa);
            if($reutnr['questionnaireanswer-idcode']){
                return ['questionnaireanswer-idcode-24'=>['请填写正确身份证/港澳台通行证']];
            }
        }
        if ($post && !\Yii::$app->request->isAjax) {
            $qnf = new QuestionNaireField();
            $qnf->userid = $this->login->userid;
            $qnf->createtime = time();
            $qnf->qnid = $id;
            $qnf->doctorid = $doctorid;
            $transaction = \Yii::$app->db->beginTransaction();

            if ($qnf->save()) {
                foreach ($post as $k => $v) {
                    if (is_array($post[$k])) {
                        foreach ($post[$k] as $pk => $pv) {
                            $qnaa = new QuestionNaireAnswer();
                            $qnaa->phone = 15811078604;
                            $qnaa->value = 'ccc';
                            $qnaa->idcode = '230107198908232610';
                            $qnaa->int = 1;
                            $qnaa->date = '2020-02-02';

                            $qnaa->answer = $pv;
                            $qnaa->doctorid = $doctorid;
                            $qnaa->qnaid = $pk;
                            $qnaa->qnid = $id;
                            $qnaa->qnfid = $qnf->id;
                            $qnaa->userid = $this->login->userid;
                            $qnaa->createtime = time();
                            if (!$qnaa->save()) {
                                \Yii::$app->getSession()->setFlash('error', array_values($qnaa->firstErrors)[0]);
                                $transaction->rollBack();
                                return $this->render('form', [
                                    'qn' => $qn,
                                    'qna' => $qna,
                                    'qnaa' => $qnaa,
                                ]);
                            }
                        }
                    }
                }
                $transaction->commit();
            }
            return $this->redirect(['naire-appoint/appoint', 'doctorid' => $doctorid, 'qid' => $qnf->id]);
        }
        return $this->render('form', [
            'qn' => $qn,
            'qna' => $qna,
            'qnaa' => $qnaa,
        ]);
    }

    public function actionDayNum($doctorid, $week = 0, $type = 9, $day, $vid = 0, $sid = 0)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $rs = [];
        $times = [];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);

        $weekv = [];
        if ($vid) {
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
        $hospitalAppointVaccineNum = HospitalAppointVaccineNum::findOne(['haid' => $hospitalA->id, 'week' => $week, 'vaccine' => $vid]);
        if ($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num - $vaccine_count <= 0) {
            return ['list' => [], 'is_appoint' => 0, 'text' => '此疫苗' . date('Y年m月d日', strtotime($day)) . "已约满，请选择其他日期" . ($hospitalAppointVaccineNum->num - $vaccine_count)];

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


            $vWeek = [];
            $vTypes = [];
            //获取疫苗预约时间（上午/下午）
            if ($vid) {
                $vaccine = Vaccine::findOne($vid);
                $query = HospitalAppointVaccine::find()
                    ->where(['haid' => $hospitalA->id, 'week' => $week]);

                if ($vaccine->type == 0) {
                    $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0]]);
                } else {
                    $query->andWhere(['or', ['vaccine' => $vid], ['vaccine' => -1]]);
                }
                $vWeek = $query->select('type')->column();

                //如果该日期只设置了上午则代表设置的上午疫苗全天可约
                $vTypes = HospitalAppointVaccine::find()->select('type')
                    ->where(['haid' => $hospitalA->id, 'week' => $week])->column();
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
                if (in_array($k, [1, 2, 3, 7, 8, 9, 10, 11, 12, 19, 20]) && !in_array(1, $vWeek) && $vWeek) {
                    $v = 0;
                } elseif (in_array($k, [4, 5, 6, 13, 14, 15, 16, 17, 18]) && !in_array(2, $vWeek) && in_array(2, $vTypes) && $vWeek) {
                    $v = 0;
                }
                $num = $v;

                if ($hospitalAppointVaccineTimeNum) {
                    if ($hospitalAppointVaccineTimeNum[$k]) {
                        if ($v >= ($hospitalAppointVaccineTimeNum[$k] - $appointVaccineNum[$k])) {
                            $num = ($hospitalAppointVaccineTimeNum[$k] - $appointVaccineNum[$k]);
                        }
                    } else {
                        $num = 0;
                    }
                }
                $rows['time'] = Appoint::$timeText[$k];
                $rows['appoint_time'] = $k;
                $rows['num'] = $num;
                $rows['num1'] = $rs_num[$k];
                $times[] = $rows;
            }
            return ['list' => $times, 'is_appoint' => $is_appoint, 'text' => ''];

        }
        return new Code(20020, '未设置');
    }

    public function actionAppoint($doctorid, $qid, $vid = 0, $sid = 0)
    {
        $type = 9;
        $dweek = ['日', '一', '二', '三', '四', '五', '六'];
        $dateMsg = ['不可约', '可约', '未放号'];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);

        $days = [];
        $weekr = str_split((string)$hospitalA->weeks);
        $cycleType = [0, 7, 14, 30];
        $cycle = $cycleType[$hospitalA->cycle];
        $delay = $hospitalA->delay;
        $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));


        //判断所选疫苗都有周几可约
        if ($vid) {
            $vaccine = Vaccine::findOne($vid);
            if ($vaccine) {
                $query = HospitalAppointVaccine::find()
                    ->select('week')
                    ->where(['haid' => $hospitalA->id])
                    ->andWhere(['vaccine' => $vid]);
                $vaccineWeek = $query->groupBy('week')->column();
                //如该疫苗无法获取周几可约则视为非法访问
                if (!$vaccineWeek) {
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
                    return new Code(20010, '社区医院暂未开通服务！');
                }
            }
        }

        if ($vaccineWeek && $streetWeek) {
            $weekr = array_intersect($vaccineWeek, $streetWeek);
        } elseif ($streetWeek || $vaccineWeek) {
            $weekr = $streetWeek ? $streetWeek : $vaccineWeek;
        }


        for ($i = 1; $i <= 60; $i++) {
            $day = $day + 86400;
            $rs['date'] = $day;
            $rs['day'] = date('m.d', $day);
            $rs['dateStr'] = date('Ymd', $day);
            $rs['week'] = date('w', $day);
            $rs['weekStr'] = $dweek[$rs['week']];
            $rs['dateState'] = $hospitalA->is_appoint($day, $weekr);
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

            $vaccines = $vQuery->asArray()->all();

            foreach ($vaccines as $k => $v) {
                $rs = $v;
                $rows[] = $rs;
            }
            $vaccines = $rows;
        } else {
            $vaccines = [];
        }
        $doctor = UserDoctor::findOne(['userid' => $doctorid]);
        if ($doctor) {
            $doctorRow = $doctor->toArray();
            $doctorRow['hospital'] = Hospital::findOne($doctor->hospitalid)->name;
        }

        return $this->render('appoint', ['qid' => $qid, 'firstday' => $days[0]['date'], 'doctorRow' => $doctorRow, 'days' => $days, 'doctor' => $doctorRow]);
    }

    public function actionSaveAppoint()
    {
        $post=\Yii::$app->request->post();
        $post['type']=9;

        $doctor = UserDoctor::findOne(['userid' => $post['doctorid']]);
        if ($doctor) {
            if(strpos($doctor->appoint,',')!==false){
                $types = explode(',',$doctor->appoint);
            }elseif ($doctor->appoint) {
                $types = str_split((string)$doctor->appoint);
            }
        }
        if (!$doctor || !$doctor->appoint || !in_array($post['type'], $types)) {
            \Yii::$app->getSession()->setFlash('error', '社区未开通');
            return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);
        };
        $appoint = HospitalAppoint::findOne(['doctorid' => $post['doctorid'], 'type' => $post['type']]);

        $w = date("w", $post['appoint_date']);
        $weeks = HospitalAppointWeek::find()
            ->andWhere(['week' => $w])
            ->andWhere(['haid' => $appoint->id])
            ->andWhere(['time_type' => $post['appoint_time']])->one();


        $appointed = Appoint::find()
            ->andWhere(['type' => $post['type']])
            ->andWhere(['doctorid' => $post['doctorid']])
            ->andWhere(['appoint_date' => $post['appoint_date']])
            ->andWhere(['appoint_time' => $post['appoint_time']])
            ->andWhere(['mode' => 0])
            ->andWhere(['<', 'state', 3])
            ->count();
        $question_naire_answer = QuestionNaireAnswer::find()->where(['qnfid' => $post['qid']])->select('answer')->indexBy('qnaid')->column();

        $appointAdult = AppointAdult::findOne(['userid' => $this->login->userid, 'name' => $question_naire_answer[22]]);
        $appointAdult = $appointAdult ? $appointAdult : new AppointAdult();
        $appointAdult->scenario = 'naire';
        $appointAdult->userid = $this->login->userid;
        $appointAdult->name = $question_naire_answer[22];
        $appointAdult->phone = $question_naire_answer[23];
        $appointAdult->gender = $question_naire_answer[35];
        $appointAdult->birthday = $question_naire_answer[34];
        $appointAdult->id_card = $question_naire_answer[24];
        $appointAdult->place = $question_naire_answer[25];
        if (!$appointAdult->save()) {
            \Yii::$app->getSession()->setFlash('error', '联系人信息保存失败');
            return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);
        }

        if ($post['vaccine']) {
            $week = date('w', $post['appoint_date']);

            $vaccine_count = Appoint::find()->where(['doctorid' => $post['doctorid'], 'vaccine' => $post['vaccine'], 'appoint_date' => $post['appoint_date']])->andWhere(['<', 'state', 3])->count();
            $hospitalAppointVaccineNum = HospitalAppointVaccineNum::findOne(['haid' => $appoint->id, 'week' => $week, 'vaccine' => $post['vaccine']]);
            if ($hospitalAppointVaccineNum && $hospitalAppointVaccineNum->num - $vaccine_count <= 0) {
                \Yii::$app->getSession()->setFlash('此疫苗' . date('Y年m月d日', $post['appoint_date']) . "已约满，请选择其他日期");
                return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);
            }
        }

        if (($weeks->num - $appointed) <= 0) {
            \Yii::$app->getSession()->setFlash('error', '该时间段已约满，请选择其他时间');
            return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);

        }


        $appoint = Appoint::findOne(['userid' => $appointAdult->userid, 'childid' => $appointAdult->id, 'type' => $post['type'], 'state' => 1]);
        if ($appoint) {
            \Yii::$app->getSession()->setFlash('error', '您有未完成的预约');
            return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);

        } elseif (!$appointAdult->userid) {
            \Yii::$app->getSession()->setFlash('error', '预约人联系信息保存失败');
            return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);

        } else {

            $model = new Appoint();
            $post['state'] = 1;
            $post['userid'] = $this->login->userid;
            $post['loginid'] = $this->login->id;
            $post['childid'] = $appointAdult->id;
            $post['phone'] = $appointAdult->phone;

            $model->load(["Appoint" => $post]);
            if (!$this->login->phone) {
                $this->login->phone = $post['phone'];
                $this->login->save();
            }
            if ($model->save()) {
                return $this->redirect(['question-naire/sign','id'=>$post['qid']]);
            } else {
                \Yii::$app->getSession()->setFlash('error', '提交失败');
                return $this->redirect(['question-naire/from', 'id'=>$post['qid'],'doctorid' => $post['doctorid']]);
            }
        }
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
        $row['child_name']=AppointAdult::findOne(['id'=>$appoint->childid])->name;
        $row['duan']=$appoint->appoint_time;
        if($appoint->vaccine==-2){
            $row['vaccineStr']='两癌筛查';
        }else {
            $vaccine = Vaccine::findOne($appoint->vaccine);
            $row['vaccineStr'] = $vaccine ? $vaccine->name : '';
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
        $appoints = Appoint::findAll(['userid' => $this->login->userid,'type'=>9,'state'=>$type]);
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
            $list[]=$row;
        }

        return $this->render('my',['list'=>$list,'type'=>$type]);
    }

    public function actionState($id,$type){
        $model=Appoint::findOne(['id'=>$id,'userid'=>$this->login->userid]);
        if(!$model){
            \Yii::$app->getSession()->setFlash('error','失败');
            return $this->redirect(['naire-appoint/my']);
        }else{

            if($type==1){
                $qnaa = QuestionNaireField::find()->where(['userid' => $this->login->userid,'state'=>1])->andWhere(['>','createtime',strtotime('-1 day')])->orderBy('id desc')->one();
                $qnaa->state=0;
                if(!$qnaa->save()){
                    var_dump($qnaa->firstErrors);exit;
                }


                $model->state=3;
            }elseif($type==2){
                $model->state=1;
            }

            if(!$model->save()) {
                \Yii::$app->getSession()->setFlash('error','失败');
            }
            return $this->redirect(['naire-appoint/my']);
        }
    }

}