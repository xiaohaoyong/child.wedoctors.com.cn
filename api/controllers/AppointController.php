<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\controllers;

use common\components\Code;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\AppointComment;
use common\models\Article;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Doctors;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\Vaccine;
use databackend\models\User;
use Da\QrCode\QrCode;
use linslin\yii2\curl\Curl;
use yii\data\Pagination;

class AppointController extends Controller
{
    public function actionDoctors($search = '')
    {
        $query = UserDoctor::find();
        $query->andWhere(['or',
            ['like','appoint','1'],
            ['like','appoint','2'],
            ['like','appoint','8'],
            ['like','appoint','6'],
            ['like','appoint','10'],
            ['like','appoint','12'],
            ['like','appoint','5']]);


        if ($search) {
            $query->andFilterWhere(['like', 'name', $search]);
        }
        if (!in_array($this->userid,[346470,390512,175579,349835,236368,240818]) && $search!='儿宝宝') {
            $query->andWhere(['city' => 11]);
            $query->andWhere(['!=','userid', 47156]);
        }
        //echo $query->createCommand()->getRawSql();exit;
        $doctors = $query->orderBy('appoint desc')->all();

        $docs = [];

        $doctorParent = DoctorParent::findOne(['parentid' => $this->userid]);
        if ($doctorParent) {
            $doctorid = $doctorParent->doctorid;
        }

        foreach ($doctors as $k => $v) {
            $rs = $v->toArray();
            $rs['name'] = Hospital::findOne($v->hospitalid)->name;
            $rs['appoint']=$v->appoint?1:0;
            if(in_array($v->userid,[184793,176156])){
                $rs['is_new'] = 1;
            }else{
                $rs['is_new'] = 0;
            }
            $docs[] = $rs;
            if ($doctorid == $v->userid) {
                $doc = $rs;
            }
        }

        return ['doctors' => $docs, 'doc' => $doc];
    }

    public function actionDoctor($id)
    {
        $uda = UserDoctor::findOne(['userid' => $id]);
        $row = $uda->toArray();
        if ($uda->appoint) {
            $types = str_split((string)$uda->appoint);
        }
        $row['type1'] = in_array(1, $types) ? 1 : 0;
        $row['type2'] = in_array(2, $types) ? 1 : 0;
        $row['type3'] = in_array(3, $types) ? 1 : 0;
        $hospital = Hospital::findOne($uda->hospitalid);

        $row['hospital'] = $hospital->name;
        return $row;
    }

    public function actionForm($id, $type)
    {
        $childs = ChildInfo::findAll(['userid' => $this->userid]);


        //doctor
        $appoint = UserDoctorAppoint::findOne(['doctorid' => $id, 'type' => $type]);
        if ($appoint) {

            $phone = $this->userLogin->phone;
            $phone = $phone ? $phone : $this->user->phone;
            $row = $appoint->toArray();

            $holiday = [
                '2018-12-30',
                '2018-12-31',
                '2019-1-1',
                '2019-2-4',
                '2019-2-5',
                '2019-2-6',
                '2019-2-7',
                '2019-2-8',
                '2019-2-9',
                '2019-2-10',
                '2019-4-5',
                '2019-4-6',
                '2019-4-7',
                '2019-5-1',
                '2019-5-2',
                '2019-5-3',
                '2019-5-4',
                '2019-6-7',
                '2019-6-8',
                '2019-6-9',
                '2019-9-13',
                '2019-9-14',
                '2019-9-15',
                '2019-10-1',
                '2019-10-2',
                '2019-10-3',
                '2019-10-4',
                '2019-10-5',
                '2019-10-6',
                '2019-10-7',
            ];

            $appoints = Appoint::find()->select("count(*)")->indexBy('appoint_time')->where(['doctorid' => $id, 'type' => $type])->groupBy('appoint_time')->column();
            $row['type1_num'] = $row['type1_num'] - $appoints[1] >= 0 ? $row['type1_num'] - $appoints[1] : 0;
            $row['type2_num'] = $row['type2_num'] - $appoints[2] >= 0 ? $row['type2_num'] - $appoints[2] : 0;
            $row['type3_num'] = $row['type3_num'] - $appoints[3] >= 0 ? $row['type3_num'] - $appoints[3] : 0;
            $row['type4_num'] = $row['type4_num'] - $appoints[4] >= 0 ? $row['type4_num'] - $appoints[4] : 0;
            $row['type5_num'] = $row['type5_num'] - $appoints[5] >= 0 ? $row['type5_num'] - $appoints[5] : 0;
            $row['type6_num'] = $row['type6_num'] - $appoints[6] >= 0 ? $row['type6_num'] - $appoints[6] : 0;

            return ['childs' => $childs, 'appoint' => $row, 'phone' => $phone, 'holiday' => $holiday];
        } else {
            return new Code(20010, '社区医院暂未开通服务！');
        }
    }

    public function actionDayNum($doctorid, $week, $type, $day)
    {
        return new Code(21000, '客户端已过期,请升级客户端');

        $rs = [];
        $appoint = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        if ($appoint) {
            $weeks = HospitalAppointWeek::find()->andWhere(['week' => $week])->andWhere(['haid' => $appoint->id])->orderBy('time_type asc')->all();
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
                return $rs;
            }
        }
        return new Code(20020, '未设置');
    }

    public function actionSave()
    {
        return new Code(21000, '客户端已过期,请升级客户端');

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


        $is_appoint = $appoint->is_appoint(strtotime($post['appoint_date']));
        if ($is_appoint != 1) {
            return new Code(21000, '预约日期非门诊日或未到放号时间!请更新客户端查看！');
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
            return new Code(20020, '您有未完成的预约');
        } elseif (!$post['childid']) {
            return new Code(20020, '请选择宝宝');
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

    public function actionView($id)
    {
        $appoint = Appoint::findOne(['id' => $id]);

        $row = $appoint->toArray();
        $doctor = UserDoctor::findOne(['userid' => $appoint->doctorid]);
        if ($doctor) {
            $hospital = Hospital::findOne($doctor->hospitalid);
        }
        $row['hospital'] = $hospital->name;
        $row['county'] = $doctor->county;

        $row['type'] = Appoint::$typeText[$appoint->type];
        $row['time'] = date('Y.m.d', $appoint->appoint_date) . "  " . Appoint::$timeText[$appoint->appoint_time];
        if ($appoint->type == 5 || $appoint->type == 6) {
            $row['child_name'] = Pregnancy::findOne($appoint->childid)->field1;

        } else {
            if($appoint->type==4 && $appoint->childid) {
                $row['child_name'] = AppointAdult::findOne($appoint->childid)->name;
            }elseif($appoint->type==10) {
                $row['child_name']=$appoint->name;
            }else{
                $row['child_name'] = ChildInfo::findOne($appoint->childid)->name;
            }
        }
        //添加儿童ID，儿童身份证
        $row['child_id'] = $appoint->childid;
        $row['child_idcard'] =  ChildInfo::findOne($appoint->childid)->idcard;

        $row['duan'] = $appoint->appoint_time;
        $vaccine = Vaccine::findOne($appoint->vaccine);
        $row['vaccineStr'] = $vaccine ? $vaccine->name : '';

        $index = Appoint::find()
            ->andWhere(['appoint_date' => $appoint->appoint_date])
            ->andWhere(['<', 'id', $id])
            ->andWhere(['doctorid' => $appoint->doctorid])
            ->andWhere(['appoint_time' => $appoint->appoint_time])
            ->andWhere(['type' => $appoint->type])
            ->count();
        $row['index'] = $index + 1;
        $row['appid']='wxfef8925dfaa329d7';
        $row['path']='/pages/index-form/index-form?productId=1107001&srcType=2';
        $row['envVersion']='release';
        $row['adv']=1;
        $row['advText']='30万元保额的疫苗保障，仅需2元，点此领取';
        if($row['doctorid']==206260) {
            $row['is_index'] = '1';
            $row['appoint_text'] = '请根据社区现场安排排队！';
        }
        $row['is_show'] = 0;
        $row['jihui'] = 0;
        if($appoint->type==2 || $appoint->type==1) {
            $row['is_show'] = 1;
            $child=ChildInfo::findOne($appoint->childid);
//            if(($child->birthday>strtotime('-74 day'))
//                || $appoint->vaccine==1
//                || $appoint->vaccine==2
//                || $appoint->vaccine==3
//                || $appoint->vaccine==6)
//            {
//                $row['jihui'] = 1;
//            }
            if($child->birthday>strtotime('2024-04-01')){
                $row['jihui'] = 1;
            }

        }

        return $row;
    }

    public function actionMy($state = 1)
    {
        if ($state == 1) {
            $appoints = Appoint::find()->andFilterWhere(['in', 'state', [1, 5]])->andWhere(['userid' => $this->userid])->andFilterWhere(['in', 'type', [1,5,6, 2,8,3,10]])->all();
        } else {
            $appoints = Appoint::findAll(['userid' => $this->userid, 'state' => $state]);
        }
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
            if ($v->type == 5 || $v->type == 6) {
                $row['child_name'] = Pregnancy::findOne($v->childid)->field1;
            } elseif($v->type==10){
                $row['child_name']=$v->name;
            }else{
                if($v->type==4 && $v->childid) {
                    $row['child_name'] = AppointAdult::findOne($v->childid)->name;
                }else{
                    $row['child_name'] = ChildInfo::findOne($v->childid)->name;
                }
            }
            $row['child_name']=$row['child_name']?$row['child_name']:"-";
            $vaccine = Vaccine::findOne($v->vaccine);
            $row['vaccineStr'] = $vaccine ? $vaccine->name : '';
			if($state==2) {
                /*查询已完成预约是否评价过 is_comt 1-已评价  2-未评价*/
                $apcomment = AppointComment::findOne(['aid' => $v->id]);
                if ($apcomment) {
                    $row['is_comt'] = 1;
                } else {
                    $row['is_comt'] = 2;
                }
            }
            $list[] = $row;
        }
        return $list;
    }

    public function actionDelete($id, $formid)
    {

        $model = Appoint::findOne(['id' => $id, 'userid' => $this->userid]);
        if (!$model->delete()) {
            return new Code(20010, '取消失败！');
        } else {
            $userLogin = $this->userLogin;
            if ($userLogin->openid) {
                $doctor = UserDoctor::findOne(['userid' => $model->doctorid]);
                if ($doctor) {
                    $hospital = Hospital::findOne($doctor->hospitalid);
                }
                $child = ChildInfo::findOne($model->childid);

                $data = [
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => ARRAY('value' => date('Y-m-d', $model->appoint_date) . " " . Appoint::$timeText[$model->appoint_time]),
                    'keyword3' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                    'keyword4' => ARRAY('value' => "已取消"),

                ];
                $rs = WechatSendTmp::sendX($data, $userLogin->xopenid, 'sG19zJw7LhBT-SrZYNJbuH1TTYtQFKfVEviXKf1ERFI', '', $formid);
            }
        }
    }

    public function actionState($id, $formid, $type, $cancel_type = 0)
    {
        $model = Appoint::findOne(['id' => $id, 'userid' => $this->userid]);
        if (!$model) {
            return new Code(20010, '取消失败！');
        } else {

            if ($type == 1) {
                $model->state = 3;
                $model->cancel_type = $cancel_type;
            } elseif ($type == 2) {
                $model->state = 1;
            }
            $userLogin = $this->userLogin;
            if ($userLogin->openid) {
                $doctor = UserDoctor::findOne(['userid' => $model->doctorid]);
                if ($doctor) {
                    $hospital = Hospital::findOne($doctor->hospitalid);
                }
                $child = ChildInfo::findOne($model->childid);
            }
            if ($model->save() && $userLogin->xopenid) {

                if ($type == 1) {
                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => date('Y-m-d', $model->appoint_date) . " " . Appoint::$timeText[$model->appoint_time]),
                        'keyword3' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                        'keyword4' => ARRAY('value' => "已取消"),
                    ];
                    $rs = WechatSendTmp::sendX($data, $userLogin->xopenid, 'sG19zJw7LhBT-SrZYNJbuH1TTYtQFKfVEviXKf1ERFI', '', $formid);

                } elseif ($type == 2) {
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
                    $rs = WechatSendTmp::sendX($data, $userLogin->xopenid, 'Ejdm_Ih_W0Dyi6XrEV8Afrsg6HILZh0w8zg2uF0aIS0', '/pages/appoint/view?id=' . $model->id, $formid);
                } else {
                    return [];
                }
            } else {
                return new Code(20011, implode(',', $model->firstErrors));
            }
        }
    }

    public function actionQrCode($id)
    {
        //QrCode::png('appoint:'.$id,false,Enum::QR_ECLEVEL_H,10);
        $qrCode = (new QrCode('appoint:'.$id))
            ->setSize(250);

        // 获取Base64编码的图片数据（假设通过GET参数传递）
        $base64Data = $qrCode->writeDataUri();

// 检查数据是否包含data URI前缀
        if (strpos($base64Data, 'data:') !== 0) {
            die('无效的Base64数据格式，需以data:开头');
        }

// 分割MIME类型和编码数据
        $parts = explode(',', $base64Data, 2);
        if (count($parts) !== 2) {
            die('Base64数据格式错误');
        }

// 提取MIME类型（例如：image/png）
        $mimeType = substr(explode(';', $parts[0])[0], 5); // 去除"data:"
        $encodedData = $parts[1];

// 解码Base64数据
        $decodedData = base64_decode($encodedData);
        if ($decodedData === false) {
            die('Base64解码失败');
        }

// 设置图片类型头并输出
        header('Content-Type: ' . $mimeType);
        echo $decodedData;

// 确保脚本终止，避免额外输出
        exit;
    }


}