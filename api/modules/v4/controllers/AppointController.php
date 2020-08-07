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
        if ($uda->appoint) {
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

        foreach (HospitalAppoint::$typeText as $k => $v) {
            if ($k == 4 or $k==7) continue;
            $rs['id'] = $k;
            $rs['name'] = $v;
            $rs['info'] = $typeInfo[$k];

            $rs['is_type'] = in_array($k, $types) ? 1 : 0;
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
        }else{
            $childs = ChildInfo::findAll(['userid' => $this->userid]);
        }

        //doctor
        $appoint = HospitalAppoint::findOne(['doctorid' => $id, 'type' => $type]);
        $userDoctor = UserDoctor::findOne(['userid' => $id]);
        if ($userDoctor->appoint) {
            $types = str_split((string)$userDoctor->appoint);
        }
        if ($appoint && in_array($type, $types)) {

            $phone = $this->userLogin->phone;
            $phone = $phone ? $phone : $this->user->phone;

            $days = [];
            $delay = $appoint->delay;
            $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));

            $dweek = ['日', '一', '二', '三', '四', '五', '六'];
            $dateMsg = ['非门诊', '门诊日', '未放号'];


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
                $hospitalAppointMonth=HospitalAppointMonth::findAll(['haid'=>$appoint->id]);
                $monthType=HospitalAppointMonth::$typeText;
            }


            return ['weekr'=>$weekr,'streets'=>$streets,'monthType'=>$monthType,'gravida_is'=>$gravida_is,'childs' => $childs,'gravida'=>$gravida,'phone' => $phone, 'vaccines' => $vaccines, 'days' => $days];

        } else {
            return new Code(20010, '社区医院暂未开通服务！');
        }

    }

    public function actionDayNum($doctorid, $week = 0, $type, $day, $vid = 0)
    {
        $rs = [];
        $times = [];
        $hospitalA = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);

        $weekv=[];
        if($vid) {
            $week = date('w', strtotime($day));
            $weekv = HospitalAppointVaccine::find()
                ->select('week')
                ->where(['haid' => $hospitalA->id])
                ->andWhere(['or', ['vaccine' => $vid], ['vaccine' => 0], ['vaccine' => -1]])->groupBy('week')->column();
        }
        $is_appoint = $hospitalA->is_appoint(strtotime($day), $weekv);
        if (!$is_appoint) {
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => '非线上预约门诊日，请选择其他日期！'];
        }
        if ($is_appoint == 2) {
            $d=HospitalAppoint::$cycleNum[$hospitalA->cycle]+$hospitalA->delay;
            $date = date('Y年m月d日', strtotime('-' .$d. ' day', strtotime($day)));
            return ['list' => [], 'is_appoint' => $is_appoint, 'text' => $date . " " . HospitalAppoint::$rtText[$hospitalA->release_time]];
        }
        $week = date('w', strtotime($day));

        $weeks = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $hospitalA->id])->all();
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
            if ($doctorid == 4119 && date('Ymd', strtotime($day)) == '20200615') {
                foreach ($rs as $k => $v) {
                    if (in_array($k, [4, 5, 6, 13, 14, 15, 16, 17, 18])) {
                        $rs[$k] = 0;
                    }
                }
            }
            if ($doctorid == 176156 && date('Ymd', strtotime($day)) == '20200522') {
                foreach ($rs as $k => $v) {
                    $rs[$k] = 0;
                }
            }
            if ($doctorid != 176156) {
                unset($rs[19]); unset($rs[20]);
            }
            $firstAppoint = Appoint::find()
                ->andWhere(['type' => $type])
                ->andWhere(['doctorid' => $doctorid])
                ->andWhere(['appoint_date' => strtotime($day)])
                ->andWhere(['!=', 'state', 3])
                ->andWhere(['mode' => 0])
                ->orderBy('createtime desc')
                ->one();
            if ($firstAppoint) {
                foreach ($rs as $k => $v) {
                    if ($firstAppoint->appoint_time > 6 && $k > 6) {
                        $rows['time'] = Appoint::$timeText[$k];
                        $rows['appoint_time'] = $k;
                        $rows['num'] = $v;
                        $times[] = $rows;

                    }
                    if ($firstAppoint->appoint_time < 7 && $k < 7) {
                        $rows['time'] = Appoint::$timeText[$k];
                        $rows['appoint_time'] = $k;
                        $rows['num'] = $v;
                        $times[] = $rows;

                    }
                }
            } else {
                foreach ($rs as $k => $v) {
                    if ($hospitalA->interval == 2 && $k > 6) {
                        $rows['time'] = Appoint::$timeText[$k];
                        $rows['appoint_time'] = $k;
                        $rows['num'] = $v;
                        $times[] = $rows;

                    }
                    if ($hospitalA->interval == 1 && $k < 7) {
                        $rows['time'] = Appoint::$timeText[$k];
                        $rows['appoint_time'] = $k;
                        $rows['num'] = $v;
                        $times[] = $rows;
                    }
                }
            }
            return ['list' => $times, 'is_appoint' => $is_appoint, 'text' => ''];

        }
        return new Code(20020, '未设置');
    }

    public function actionSave()
    {
        $post = \Yii::$app->request->post();

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
            return new Code(21000, '您的版本过低，请更新版本后预约（点击左上角三个点选择重新进入小程序）');
        }


        //体检限制月龄预约
        if($post['type']==1 && $post['childid']){
            $hospitalAppointMonth = HospitalAppointMonth::find()->select('month')->where(['type' => $post['month']])->andWhere(['haid'=>$appoint->id])->orderBy('month asc')->column();

            if($hospitalAppointMonth && $appoint->is_month) {
                $child = ChildInfo::findOne($post['childid']);
                if ($child) {
                    $first = $hospitalAppointMonth[0];
                    $end = $hospitalAppointMonth[count($hospitalAppointMonth) - 1];
                    $daytime =strtotime($post['appoint_date']);
                    if (strtotime("-$first month", $daytime) < $child->birthday || strtotime("-" . ($end + 1) . " month", $daytime) > $child->birthday) {
                        return new Code(21000,date('Y年m月d日',$daytime).HospitalAppointMonth::$typeText[$post['month']]
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
            $week = date('w', strtotime($post['appoint_date']));
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


        $appoint = Appoint::findOne(['childid' => $post['childid'], 'type' => $post['type'], 'state' => 1]);
        if ($appoint) {
            return new Code(21000, '您有未完成的预约');
        } elseif (!$post['childid']) {
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