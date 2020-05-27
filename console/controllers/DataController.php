<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/5/2
 * Time: 上午11:32
 */

namespace console\controllers;


use api\modules\v2\controllers\ExaController;
use app\models\Login;
use callmez\wechat\sdk\components\BaseWechat;
use callmez\wechat\sdk\MpWechat;
use callmez\wechat\sdk\Wechat;
use common\components\HttpRequest;
use common\components\wx\WxBizDataCrypt;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\Area;
use common\models\Article;
use common\models\ArticleComment;
use common\models\ArticleUser;
use common\models\Autograph;
use common\models\BabyGuide;
use common\models\BabyTool;
use common\models\BabyToolTag;
use common\models\ChatRecord;
use common\models\ChildInfo;
use common\models\DataUpdateRecord;
use common\models\DataUser;
use common\models\DoctorParent;
use common\models\Doctors;
use common\models\Examination;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointWeek;
use common\models\HospitalForm;
use common\models\Interview;
use common\models\Log;
use common\models\Notice;
use common\models\Pregnancy;
use common\models\Test;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\Vaccine;
use common\models\WeOpenid;
use EasyWeChat\Factory;
use Faker\Provider\File;
use OSS\Core\OssException;
use OSS\OssClient;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use saviorlv\aliyun\Sms;
use yii\base\Controller;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


class DataController extends Controller
{
    public function dir_a($file)
    {
        $files = [];
        //1、首先先读取文件夹
        $temp = scandir($file);
        //遍历文件夹
        foreach ($temp as $v) {
            $a = $file . '/' . $v;
            echo $a;
            echo "\n";
            if ($v === '.' || $v === '..' || $v === '.git' || $v === '.idea' || $v === 'upload') {
                continue;
            }
            if (is_dir($a)) {
                $filesa = $this->dir_a($a);
                $files = array_merge($files, $filesa);

            } else {
                $files[] = $a;
            }
        }
        return $files;
    }

    public function actionTesta()
    {
//        $hav=HospitalAppointVaccine::findAll(['haid'=>98]);
//        $appoints=Appoint::find()->andWhere(['type'=>2])->andWhere(['>','vaccine',0])->andWhere(['>','appoint_date',time()])->all();
//        foreach($appoints as $k=>$v){
//
//            $hospitalAppoint=HospitalAppoint::find()->where(['type'=>2])->andWhere(['doctorid'=>$v->doctorid])->one();
//            $hav=HospitalAppointVaccine::find()->select('week')->where(['haid'=>$hospitalAppoint->id])->andWhere(['vaccine'=>$v->vaccine])->column();
//            $w=date('w',$v->appoint_date);
//            if(!in_array($w,$hav))
//            {
//                echo $v->id;
//                echo "==".$v->vaccine;
//                echo "==".$w;
//                echo "\n";
//            }
//        }
//        exit;


        $stime = strtotime('2019-01-01');
        $doctors = UserDoctor::find()->all();
        foreach ($doctors as $k => $v) {
            for ($stime = $stime; $stime < time(); $stime = strtotime('+1 day', $stime)) {
                $etime = strtotime('+1 day', $stime);
                $doctorParent = DoctorParent::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['>=', 'createtime', $stime])
                    ->andWhere(['<', 'createtime', $etime])
                    ->count();


                $child_info1=ChildInfo::find()
                    ->leftJoin('doctor_parent','doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid'=>$v->userid])
                    ->leftJoin('autograph','autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'autograph.createtime', $stime])
                    ->andWhere(['<', 'autograph.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year',$stime)])
                    ->andWhere('autograph.createtime>=child_info.createtime')
                    ->count();
                $child_info2=ChildInfo::find()
                    ->leftJoin('doctor_parent','doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid'=>$v->userid])
                    ->leftJoin('autograph','autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'child_info.createtime', $stime])
                    ->andWhere(['<', 'child_info.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year',$stime)])
                    ->andWhere('autograph.createtime<child_info.createtime')
                    ->count();

                $pregLCount=Pregnancy::find()
                    ->andWhere(['pregnancy.field49'=>0])
                    ->andWhere(['>', 'pregnancy.familyid', 0])
                    ->leftJoin('autograph', '`autograph`.`userid` = `pregnancy`.`familyid`')
                    ->andWhere(['>=', 'autograph.createtime', $stime])
                    ->andWhere(['<', 'autograph.createtime', $etime])
                    ->andFilterWhere(['`autograph`.`doctorid`' => $v->userid])->count();


                $appoint = Appoint::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['appoint_date' => $stime])
                    ->andWhere(['!=', 'state', 3])
                    ->count();
                $rs = [];
                $rs['sign1'] = $doctorParent;
                $rs['sign2'] = $child_info1+$child_info2;

                $rs['appoint_num'] = $appoint;
                $rs['doctorid'] = $v->userid;
                $rs['date'] = $stime;
                $hospitalFrom = HospitalForm::find()->where(['doctorid' => $v->userid])->andWhere(['date' => $stime])->one();
                $hospitalFrom = $hospitalFrom ? $hospitalFrom : new HospitalForm();
                $hospitalFrom->load(['HospitalForm' => $rs]);
                $hospitalFrom->save();
                var_dump($hospitalFrom->firstErrors);
            }
        }
        exit;

        ini_set('memory_limit', '6000M');

        //$appoint=Appoint::find()->where(['doctorid'=>176156])->andWhere(['state'=>1])->andWhere(['>','appoint_time',6])->groupBy('userid')->all();

        $userids = [];
        $k = 0;
        $child = ChildInfo::find()->select('userid')->where(['<', 'birthday', 1575129600])->andWhere(['>', 'doctorid', 0])->orderBy('birthday desc')->groupBy('userid')->all();
        foreach ($child as $k => $v) {
            $login = UserLogin::find()->where(['userid' => $v->userid])->andWhere(['!=', 'openid', ''])->all();
            foreach ($login as $lk => $lv) {
                if (!in_array($lv->openid, $userids)) {
                    $userids[] = $lv->openid;
                    $k++;

                    echo $k."\n";

                    $data = [
                        'first' => ['value' => '母乳喂养是为婴儿健康生长和发育提供理想的食品和营养的无与伦比的方式，同时也是生殖过程的基本组成部分和母亲健康的重要指标，为此儿宝宝邀请了侯景丽主任为大家讲解《母乳喂养指南》是如何来解决宝宝母乳喂养的问题。'],
                        'keyword1' => ARRAY('value' => '母乳喂养指南，第六期健康直播课即将开始'),
                        'keyword2' => ARRAY('value' => '2020年5月24日下午3点'),
                        'remark' => ARRAY('value' => ""),
                    ];
                    $rs = WechatSendTmp::send($data, $lv->openid, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://wx1147c2e491dfdf1d.h5.xiaoe-tech.com/content_page/eyJ0eXBlIjoiMiIsInJlc291cmNlX3R5cGUiOjQsInJlc291cmNlX2lkIjoibF81ZWM3NGFiZTI5NDU2X1pwYkZhaGJMIiwiYXBwX2lkIjoiYXBwc3gwdjlxOEk4MzMxIiwicHJvZHVjdF9pZCI6IiJ9');

                    var_dump($rs);
                } else {
                    echo "=================";
                }
            }
        }
        $data = [
            'first' => ['value' => '母乳喂养是为婴儿健康生长和发育提供理想的食品和营养的无与伦比的方式，同时也是生殖过程的基本组成部分和母亲健康的重要指标，为此儿宝宝邀请了侯景丽主任为大家讲解《母乳喂养指南》是如何来解决宝宝母乳喂养的问题。'],
            'keyword1' => ARRAY('value' => '母乳喂养指南，第六期健康直播课即将开始'),
            'keyword2' => ARRAY('value' => '2020年5月24日下午3点'),
            'remark' => ARRAY('value' => ""),
        ];
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://wx1147c2e491dfdf1d.h5.xiaoe-tech.com/content_page/eyJ0eXBlIjoiMiIsInJlc291cmNlX3R5cGUiOjQsInJlc291cmNlX2lkIjoibF81ZWM3NGFiZTI5NDU2X1pwYkZhaGJMIiwiYXBwX2lkIjoiYXBwc3gwdjlxOEk4MzMxIiwicHJvZHVjdF9pZCI6IiJ9');

        $data = [
            'first' => ['value' => '母乳喂养是为婴儿健康生长和发育提供理想的食品和营养的无与伦比的方式，同时也是生殖过程的基本组成部分和母亲健康的重要指标，为此儿宝宝邀请了侯景丽主任为大家讲解《母乳喂养指南》是如何来解决宝宝母乳喂养的问题。'],
            'keyword1' => ARRAY('value' => '母乳喂养指南，第六期健康直播课即将开始'),
            'keyword2' => ARRAY('value' => '2020年5月24日下午3点'),
            'remark' => ARRAY('value' => ""),
        ];
        $rs = WechatSendTmp::send($data, 'o5ODa0wJpc-GaCJuAl7NnE5nnfsM', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://wx1147c2e491dfdf1d.h5.xiaoe-tech.com/content_page/eyJ0eXBlIjoiMiIsInJlc291cmNlX3R5cGUiOjQsInJlc291cmNlX2lkIjoibF81ZWM3NGFiZTI5NDU2X1pwYkZhaGJMIiwiYXBwX2lkIjoiYXBwc3gwdjlxOEk4MzMxIiwicHJvZHVjdF9pZCI6IiJ9');

        var_dump($rs);
        exit;
        exit;



        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        var_dump($app->rebind());
        exit;


        $doctors = UserDoctor::find()->all();
        foreach ($doctors as $dk => $dv) {
            echo $dv->name . "\n";
            for ($i = 1; $i < 32; $i++) {

                $time = strtotime('+' . $i . " day");
                $time = strtotime(date('Ymd', $time));
                $appoints1 = Appoint::find()->select('count(*) as c,appoint_time')->where(['appoint_date' => $time])->andWhere(['doctorid' => $dv->userid])->andWhere(['type' => 2])->andWhere(['state' => 1])->andWhere(['mode' => 0])->andWhere(['>', 'appoint_time', 6])->count();
                $appoints2 = Appoint::find()->select('count(*) as c,appoint_time')->where(['appoint_date' => $time])->andWhere(['doctorid' => $dv->userid])->andWhere(['type' => 2])->andWhere(['state' => 1])->andWhere(['mode' => 0])->andWhere(['<', 'appoint_time', 7])->count();
                if ($appoints1 && $appoints2) {
                    echo date('Ymd', $time) . "==" . $dv->userid;
                    echo "\n";
                }
            }
            echo "==================================\n";
        }
        exit;

        $appoints = Appoint::find()->where(['>', 'appoint_date', time()])->andWhere(['type' => 2])->andWhere(['state' => 1])->andWhere(['mode' => 0])->all();
        foreach ($appoints as $k => $v) {

            $query = Appoint::find()->where(['appoint_date' => $v->appoint_date])->andWhere(['type' => 2])->andWhere(['state' => 1])->andWhere(['mode' => 0]);
            if ($v->appoint_time > 6) {
                $query->andWhere(['<', 'appoint_time', 7]);
            } else {
                $query->andWhere(['>', 'appoint_time', 6]);
            }
            $count = $query->count();
            if ($count > 0) {
                echo $v->appoint_date . "," . $v->doctorid . "\n";
            }

        }
        exit;

        $test = Test::find()->all();
        foreach ($test as $k => $v) {
            $child = ChildInfo::find()->where(['name' => $v->f6])->andWhere(['birthday' => strtotime($v->f8)])->andWhere(['source' => 110620])->one();
            if ($child) {
                $userParent = UserParent::findOne(['userid' => $child->userid]);
                if ($userParent) {
                    if ($userParent->mother != $v->f10 || $userParent->father != $v->f12) {
                        echo "false parent:" . $userParent->userid . $v->f6 . "\n";
                    }
                } else {
                    echo "no parent:" . $v->f6 . "\n";
                }
            }
        }
        exit;

        $child = ChildInfo::find()->where(['source' => 110620])->all();
        foreach ($child as $k => $v) {

            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid, 'level' => 1]);
            if (!$doctorParent) {
                $user = User::findOne($v->userid);
                if ($user) {
                    $user->delete();
                }
            }
        }
        exit;

        $query = ChildInfo::find();
        $query->select('userid');
        $query->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`');
        $query->andFilterWhere(['`doctor_parent`.`doctorid`' => 113896]);
        $query->andFilterWhere(['`doctor_parent`.`level`' => 1]);
        $query->andFilterWhere(['child_info.admin' => 110581]);
        $query->andFilterWhere(['>', '`child_info`.birthday', strtotime("-6 year")]);

        $au = Autograph::find()->select('userid')->where(['doctorid' => 113896])->column();
        $list = $query->andWhere(['in', 'child_info.userid', $au])->all();


        foreach ($list as $k => $v) {
            $userParent = UserParent::findOne(['userid' => $v->userid]);
            if ($userParent) {
                echo $v->userid . "," . $userParent->mother . "\n";
            }
        }
        exit;


        ini_set('memory_limit', '2048M');

        $doctors = UserDoctor::find()->all();
        foreach ($doctors as $dk => $dv) {
            echo $dv->name . "\n";
            for ($i = 1; $i < 32; $i++) {

                $time = strtotime('+' . $i . " day");
                $time = strtotime(date('Ymd', $time));
                $appoints = Appoint::find()->select('count(*) as c,appoint_time')->where(['appoint_date' => $time])->andWhere(['doctorid' => $dv->userid])->andWhere(['type' => 2])->andWhere(['state' => 1])->andWhere(['mode' => 0])->groupBy('appoint_time')->asArray()->all();
                if ($appoints) {
                    echo date('Ymd', $time) . "\n";
                    foreach ($appoints as $ak => $av) {
                        echo $av['appoint_time'] . ":" . $av['c'] . "===";
                    }
                    echo "\n";
                }
            }
            echo "==================================\n";
        }
        exit;


        $h = UserDoctor::find()->all();
        foreach ($h as $hk => $hv) {
            echo $hv->userid . "==";
            $doctor = DoctorParent::find()->where(['level' => 1])->andWhere(['doctorid' => $hv->userid])->all();
            foreach ($doctor as $k => $v) {
                $child_info = ChildInfo::find()->where(['userid' => $v->parentid])->all();
                if ($child_info) {
                    $child_info = ChildInfo::find()->where(['userid' => $v->parentid])->andWhere(['doctorid' => $hv->hospitalid])->all();
                    if (!$child_info) {
                        ChildInfo::updateAll(['doctorid' => $hv->hospitalid], 'userid =' . $v->parentid);
                        echo $v->doctorid . "==" . $v->parentid;
                        echo "\n";
                    }
                }
            }
        }
        exit;


        $query = \common\models\Pregnancy::find()
            ->andWhere(['pregnancy.field49' => 0])
            ->andWhere(['>', 'pregnancy.familyid', 0])
            ->andWhere(['>', 'pregnancy.field16', strtotime('-11 month')])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
            ->andWhere(['>', 'doctor_parent.level', 0]);
        $query->andWhere(['>=', 'doctor_parent.createtime', strtotime('2020-02-01')])->andWhere(['<=', 'doctor_parent.createtime', strtotime('2020-02-29')]);

        echo $query->count();
        exit;


//        $appoint=Appoint::find()->where(['doctorid'=>176156])->andWhere(['state'=>1])->andWhere(['>','appoint_time',6])->groupBy('userid')->all();
//        foreach($appoint as $k=>$v){
//            $login=UserLogin::find()->where(['id'=>$v->loginid])->one();
//            if($login && $login->openid) {
//
//                $data = [
//                    'first' => ['value' => '尊敬的儿童家长您好，由于您之前预约的铁营社区' . date('Y年m月d月', $v->appoint_date) . ' '. Appoint::$timeText[$v->appoint_time].'接种号为系统和社区联调的测试号，已经超出了社区医院可承受的服务极限，现已取消，请您预约其他时间段，给您带来的不便深感歉意,感谢您一直以来对铁营社区和儿宝宝的支持'],
//                    'keyword1' => ARRAY('value' => '铁营社区卫生服务中心'),
//                    'keyword2' => ARRAY('value' => 87644342),
//                    'keyword3' => ARRAY('value' => '如有问题可联系儿宝宝小助手（erbbzs）或拨打社区医院电话'),
//                    'remark' => ARRAY('value' => ""),
//                ];
//                $rs = WechatSendTmp::send($data, $login->openid, '3ui_xwyZXEw4DK4Of5FRavHDziSw3kiUyeo74-B0grk', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=1',]);
//            }
//            sleep(1);
//            $response = \Yii::$app->aliyun->sendSms(
//                "儿宝宝", // 短信签名
//                "SMS_187540377", // 短信模板编号
//                $v->phone, // 短信接收者
//                Array(  // 短信模板中字段的值
//                    "date" => date('Y年m月d月',$v->appoint_date),
//                    "shijianduan" => Appoint::$timeText[$v->appoint_time],
//                )
//            );
//            $response = json_decode($response, true);
//            $v->state=3;
//            $v->save();
//            sleep(1);
//        }
//        exit;
    }

    public function actionTests()
    {
        $doctorParent = DoctorParent::find()->where(['doctorid' => 113890])->andWhere(['level' => 1])->all();
        foreach ($doctorParent as $k => $v) {
//            $child=ChildInfo::find()
//                ->where(['userid'=>$v->parentid])
//                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
//                ->andWhere(['>', 'child_info.doctorid', 0])
//                ->all();
            $child = Pregnancy::find()->where(['familyid' => $v->parentid])->andWhere(['field49' => 0])->all();
            if ($child) {
                $rs = [];
                //$parent=UserParent::findOne(['userid'=>$v->parentid]);
                foreach ($child as $ck => $cv) {
                    $rs[] = '11010538';
                    $rs[] = '11010538';
                    $rs[] = 'XHMWN';
                    $rs[] = $cv->field1;
                    $rs[] = date("Y-m-d", $cv->field2);
                    $rs[] = $cv->field6;
                    $rs[] = $cv->field4;
                    $rs[] = "1";
                    $rs[] = "0";
                    $rs[] = "0";
                    $rs[] = date("Y-m-d", $v->createtime);
                    $rs[] = "0";
                    $rs[] = "0538";
                    $rs[] = "北京市朝阳区小红门社区卫生服务中心";
                    $rs[] = "XHMWN";
                    $rs[] = "2019/9/19";
                    echo implode(',', $rs);
                    $rs = [];
                    echo "\n";
                }
            }
        }
    }

    public function actionAppoint()
    {
        $userAppoint = UserDoctorAppoint::find()->all();
        foreach ($userAppoint as $k => $v) {
            $a = $v->toArray();
            print_r($a);
            $weeks = str_split((string)$v->weeks);
            $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $v->doctorid, 'type' => $v->type]);
            $hospitalAppoint = $hospitalAppoint ? $hospitalAppoint : new HospitalAppoint();
            $hospitalAppoint->doctorid = $v->doctorid;
            $hospitalAppoint->cycle = $v->cycle;
            $hospitalAppoint->delay = $v->delay;
            $hospitalAppoint->type = $v->type;
            $hospitalAppoint->week = $weeks;
            $hospitalAppoint->save();
            $id = $hospitalAppoint->id;
            $row = [];
            foreach ($weeks as $wk => $wv) {
                for ($i = 1; $i <= 6; $i++) {
                    $rs = [];
                    $rs[] = $wv;
                    $rs[] = $i;
                    $name = 'type' . $i . "_num";
                    $rs[] = $a[$name];
                    $rs[] = $id;
                    $row[] = $rs;
                }
            }
            \Yii::$app->db->createCommand()->batchInsert(HospitalAppointWeek::tableName(), ['week', 'time_type', 'num', 'haid'],
                $row
            )->execute();
        }
    }

    public function actionPush()
    {
        $phones = [
            18201599388,
            15810423758,
            13488789894,
            13581573919,
            17701133177,
            18810873734,
            17812003996,
            13970138080,
            15711103486,
            18612550502,
            18610327998,
            13935898710,
            18911836010,
            18612003631,
            15010344309,
            15321797996,
            18801150325,
            13240287336,
            13381026325,
            18611619771,
            18910709931,
            17610798331,
            18911820229,
            13810366926,
            15811078604,
        ];

        foreach ($phones as $k => $v) {
            $response = \Yii::$app->aliyun->sendSms(
                "儿宝宝", // 短信签名
                "SMS_157275701", // 短信模板编号
                $v, // 短信接收者
                Array(  // 短信模板中字段的值
                    "title" => "早产宝宝该如何护理和喂养",
                    "datetime" => "2019年1月26日 14:00",
                    "hospital" => "美中宜和妇儿医院",
                    "address" => "北京市朝阳区安慧北里逸园5号楼",
                )
            );
            $response = json_decode($response, true);
            var_dump($response);
        }
        exit;

//        $doctorParent=DoctorParent::find()->select('parentid')->where(['doctorid'=>4135])->column();
//        $preg=Pregnancy::find()->select('field1')->where(['in','familyid',$doctorParent])->andWhere(['source'=>0])->column();
//        var_dump($preg);exit;
//        $doctorParent = DoctorParent::find()->where(['doctorid'=>0])->all();
//        foreach($doctorParent as $k=>$v){
//
//            $child=ChildInfo::findOne(['userid'=>$v->parentid]);
//            if($child && $child->source)
//            {
//                $child->doctorid=$child->source;
//                $child->save();
//                $doctorid=UserDoctor::findOne(['hospitalid'=>$child->source])->userid;
//                $v->doctorid=$doctorid;
//                $v->save();
//            }
//
//        }
        //exit;


        $childs = ChildInfo::find()->select(UserLogin::tableName() . ".openid")
            ->andWhere([ChildInfo::tableName() . '.field42' => 37])
            ->andWhere(['>', ChildInfo::tableName() . '.birthday', 1485878400])
            ->leftJoin(UserLogin::tableName(), UserLogin::tableName() . ".userid=" . ChildInfo::tableName() . ".userid")
            ->andWhere(['!=', UserLogin::tableName() . ".openid", ''])
            ->groupBy(UserLogin::tableName() . ".openid")
            ->column();
        //var_dump($childs);exit;
        $openids = ["o5ODa0--aywXYjum-UJl3gjZWbmo",
            "o5ODa0-02li3y9dj8SyeUp3cSFF8",
            "o5ODa0-0ImAzRWN1S47X8SjWYLHQ",
            "o5ODa0-2vWpkGm5kSUR5DnF-aPoE",
            "o5ODa0-3MD67GZ1oEE_Lq1dDn108",
            "o5ODa0-4BH897lFFWXxF3cuCjHIA",
            "o5ODa0-4ecwpN1PDq_f8znIGzl6M",
            "o5ODa0-5-EznjYvsPs8NMrY1f6AI",
            "o5ODa0-6IHYD5VNw1V9Z1Z-TUNPQ",
            "o5ODa0-6wD1x1RQxafBvg-WcIO4o",
            "o5ODa0-7gKErEFk5CpdDMnaiWYK4",
            "o5ODa0-AEnmla9QNqVhxHbpZEjIU",
            "o5ODa0-AocCvrPKsokV4cj9RhTsM",
            "o5ODa0-b7t0DwLM-YXY5ktIQBii0",
            "o5ODa0-bQPN-QJObsB6LvDR0cCj8",
            "o5ODa0-bq_K9q-wOK8yZSnBYit5A",
            "o5ODa0-BrGqGIBQ65nUPUvXtIWU0",
            "o5ODa0-d_cOB3dpSgX314q4KWSvA",
            "o5ODa0-f9dhFWMQozaJtmLICAfNI",
            "o5ODa0-FVpjzg7K7P-RFJ0FtDlf8",
            "o5ODa0-fZ-CxZOaZ7tIUh7z9OoNU",
            "o5ODa0-gnMJ9RU-XNG-ctReRyRus",
            "o5ODa0-GzPRfJl27-_aidG7BdEHI",
            "o5ODa0-H9FtiVwFuSP1VgmGDL6ic",
            "o5ODa0-HlJAdHupG_WbrzStwSRrw",
            "o5ODa0-IL7Ss2DZmVKNAnHIjK6qc",
            "o5ODa0-ILgeYHghN87QgQcC7AUo0",
            "o5ODa0-Is2yDOauZfKP8xiItsyZo",
            "o5ODa0-J8kYIdOYqPSkGhA2KhTig",
            "o5ODa0-jAwtAzIUUn8YbZ37WB0UY",
            "o5ODa0-Jcl-7t6S-0T7m4jhoco8M",
            "o5ODa0-jI105LmM6jthcslZh7lTc",
            "o5ODa0-k1rHl8mjPcVvzYh8LRRss",
            "o5ODa0-KMqIzB3y-B8Y3YhptAUno",
            "o5ODa0-KvEQumyzOJIbq56zoMAJw",
            "o5ODa0-lqTLOIXNxiYR8Pkn1f_5o",
            "o5ODa0-lZB_CFzFPRdpnVXVXVYA4",
            "o5ODa0-MuFu7CFCvGsGSJukpqr7o",
            "o5ODa0-N8VdKRq2faFfAgrco7aZ4",
            "o5ODa0-Nc9qSofborETNeoLHYJGE",
            "o5ODa0-NVLJYLfEGgud900yBSYdU",
            "o5ODa0-O8X0mIIYuWybQF0vE1bmU",
            "o5ODa0-oqqkT61WGA3MxAFxIwmHc",
            "o5ODa0-pXs7E5oWsUezd5Y4F8yy0",
            "o5ODa0-Q-G0l_W7Y_sHArWoIk_0M",
            "o5ODa0-Q5eT2_MZMy5zp-xkqd7sI",
            "o5ODa0-qbSr7GUpIbnBv5JYol_5A",
            "o5ODa0-QBWfQo1pYks_jxpLtnSOo",
            "o5ODa0-QPO84MiH17hGQ8mGl_W3g",
            "o5ODa0-RIZJ1hG0k8Xch5lqRRam0",
            "o5ODa0-rlIVnjheYEoWXW9_5viq8",
            "o5ODa0-r_CbdPWLNdPCsGdPcD_J8",
            "o5ODa0-S-VWLraJ0jMwHx86jyapc",
            "o5ODa0-SgGZFsAA4jYYoxgH4gAPQ",
            "o5ODa0-sMuWFIXuIiD5HNRfVMYBg",
            "o5ODa0-sQ0zUqcZOADvbn-zgy2TM",
            "o5ODa0-sSYgfG_4REufHiCIKCeds",
            "o5ODa0-sue7lrP_GjfzMcVJXO528",
            "o5ODa0-svcRgo-5OLQTuUFku12s0",
            "o5ODa0-TIxUQhqTft6XLnQC5RtEU",
            "o5ODa0-tk1DDP-WHUI_UEL-7Oic0",
            "o5ODa0-v3jsW63Ca7Q3UtCoZVbHQ",
            "o5ODa0-vKvMsOScqDfRPsWPz7JLg",
            "o5ODa0-vy8mkpmkwHnzomVK7tkaA",
            "o5ODa0-vztmIx9HjwP5JvNi4D9GU",
            "o5ODa0-WQ0WNg49xCm9l2ovStO-I",
            "o5ODa0-WVq1WSafEePos1yfKFgcM",
            "o5ODa0-Y0RZ5XR8SB1xICnaCgX8g",
            "o5ODa0-YDtj9OTvyYvz9SWgJ5kws",
            "o5ODa0-zpHn46q6uuwRumq9UMIzo",
            "o5ODa0-ZtUkeutKUjIfIQdTzDc7M",
            "o5ODa0-_-8lo7lk5c_zRGsKJtkdw",
            "o5ODa0-_Gd_-rsottz7CqmazAinU",
            "o5ODa00-6jL6m5xanUMX_F_rN-cI",
            "o5ODa00-ijvNGQNuKAg_sxcWpwDQ",
            "o5ODa00-jVB8n23Vva5B5FKl35-A",
            "o5ODa000BukDVD2dvz9q6kEF2I9o",
            "o5ODa000Ea4L-g3WAEKUj2dnatb8",
            "o5ODa000lKEBhNmSmvzG-pGWsFx0",
            "o5ODa001NPfICkDBM66M83zlzaEk",
            "o5ODa002EwxPcGeBdJDZn6lzargY",
            "o5ODa004cZczvurB_KizHuTCsh1I",
            "o5ODa005R7TK0QwUjtsIRag8VtGw",
            "o5ODa005VVhB42Mbi71s2wmW5KmU",
            "o5ODa006Re90x6MJATGGECeXi8ts",
            "o5ODa006u_g94cGLn7PxEjlTioPk",
            "o5ODa008ou3pa5m4yWQTrxQbycXk",
            "o5ODa0091Z6hpLzKLMTgM1zN-AwI",
            "o5ODa009MPsxc7tL4SfqKF4AX4p8",
            "o5ODa00aj7vC4Bv5pnjndzF2zhhw",
            "o5ODa00AoHLPihn-9LZampIwwkBI",
            "o5ODa00b6B_tGr5iaSXpoh-pFY9o",
            "o5ODa00B969sSbXlFOzJ47n48f94",
            "o5ODa00Du77OnR9Qsr6UHDQRZhpc",
            "o5ODa00DwGwl8J4xbUMJ5bDeMjis",
            "o5ODa00EqMeQhViy_L1XgHAID_vg",
            "o5ODa00EwEdMU0KeHLrgy5mPsn4E",
            "o5ODa00f2mURa32gHtoUidfJRQTY",
            "o5ODa00F7gIFLzq5eotHuptXtG1I",
            "o5ODa00FBnjxqE7Tsc_KILdYpSsE",
            "o5ODa00fJXMmQ7YkirtT3lUmKOuE",
            "o5ODa00FYB-J1QA8ksmEsGlSRBaM",
            "o5ODa00gGCtvpG5M8AoELxYhFeIM",
            "o5ODa00gy2WXs1eoyZN_ansWjhGQ",
            "o5ODa00hPmG7-x_ZogRR-4EG_h90",
            "o5ODa00Hqab0glUFKOhNKrXeIxHs",
            "o5ODa00iyxbFRbCBtfjW_2yyj5O8",
            "o5ODa00JhChW4ty6gq0yd_aUdwS4",];
        foreach ($childs as $k => $v) {
            if (!in_array($v, $openids)) {
                $data = [
                    'first' => array('value' => "家长您好，近期有个免费讲座邀请您参加\n",),
                    'keyword1' => ARRAY('value' => "早产宝宝该如何护理和喂养-李瑛主任"),
                    'keyword2' => ARRAY('value' => "2019-01-26 14:00"),
                    'remark' => ARRAY('value' => "地点：美中宜和妇儿医院（朝阳区安慧北里逸园5号楼）名额有限，速速报名"),
                ];
                //var_dump($doctor->name);
                $rs = WechatSendTmp::send($data, $v, 'u1B7beQlAmsvM_1HkW9nVtzv4Yr2CJ_dOx9WzFYCAmI', 'https://jinshuju.net/f/hfZOIr');
                $openids[] = $v;
                echo $v . "" . $rs . "\n";
            } else {
                echo "jump\n";
            }
        }
        exit;
    }

    public function actionDoctorChild()
    {
        $doctorids = [];
        $weopenid = WeOpenid::findAll(['level' => 0]);
        foreach ($weopenid as $k => $v) {
            $doctor = $doctorids[$v->doctorid];
            if (!$doctor) {
                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                $doctorids[$v->doctorid] = $doctor;
            }

            $data = [
                'first' => array('value' => "您好，为确保享受儿童中医药健康指导服务,请完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => $doctor->name),
                'remark' => ARRAY('value' => "\n 点击授权并完善宝宝信息，如果已添加宝宝请忽略此条提醒", 'color' => '#221d95'),
            ];
            //var_dump($doctor->name);
            $rs = WechatSendTmp::send($data, $v->openid, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
            echo $v->openid . "\n";
            usleep(300000);
        }
        exit;

        $doctorParent = DoctorParent::find()->andWhere(['level' => 1])->all();
        foreach ($doctorParent as $k => $v) {
            $child = ChildInfo::findOne(['userid' => $v->parentid]);
            if (!$child) {
                echo $v->parentid . "\n";
            }
        }
    }

    public function actionDeleteMotherFather()
    {

        $file = fopen('test3.log', 'r');
        while (($line = fgets($file)) !== false) {
            $tmp = explode(',', trim($line));
            $this->userLogin($tmp[0], $tmp[1]);
            $this->doctorParent($tmp[0], $tmp[1]);
            $this->articleUser($tmp[0], $tmp[1]);
            $this->child($tmp[0], $tmp[1]);
            echo "==" . $tmp[0] . "," . $tmp[1] . "\n";
            $user = User::findOne($tmp[1]);
            if ($user) {
                $user->delete();
            } else {
                $userParent = UserParent::findOne(['userid' => $tmp[1]]);
                $userParent->delete();
            }
        }
        exit;


        $userParent = UserParent::find()->select('count(*) as c,mother_id,mother,father')
            ->groupBy('mother_id,father')->andWhere(['!=', 'mother', ''])->andWhere(['!=', 'mother_id', ''])
            ->andWhere(['!=', 'father', ''])->andWhere(['!=', 'source', 0])->having('c > 1')->all();
        foreach ($userParent as $k => $v) {
            $tmp = [];
            $master = [];
            $up = UserParent::find()->where(['mother' => $v->mother])->andWhere(['father' => $v->father])->orderBy('userid desc')->all();
            foreach ($up as $uk => $uv) {
                $tmp[] = array_filter($uv->toArray(), function ($v) {
                    return $v ? true : false;
                });
                if (!$uk) {
                    $master = $tmp[$uk];
                } else {
                    if (count($tmp[$uk]) > count($tmp[$uk - 1])) {
                        $master = $tmp[$uk];
                    }
                }
            }
            foreach ($tmp as $tk => $tv) {
                if ($master['userid'] != $tv['userid']) {
                    $this->userLogin($master['userid'], $tv['userid']);
                    $this->doctorParent($master['userid'], $tv['userid']);
                    $this->articleUser($master['userid'], $tv['userid']);
                    $this->child($master['userid'], $tv['userid']);
                    echo "==" . $master['userid'] . "," . $tv['userid'] . "\n";
                    $user = User::findOne($tv['userid']);
                    if ($user) {
                        $user->delete();
                    } else {
                        $userParent = UserParent::findOne(['userid' => $tv['userid']]);
                        $userParent->delete();
                    }
                }
            }
        }
    }

    public function actionDatac()
    {

        $dataUser = DataUser::find()->all();
        foreach ($dataUser as $k => $v) {
            $v->token = md5($v->id . "wwdsa");
            $v->save();

        }
        exit;


        var_dump(__LOG__);
        exit;
        $babyTag = BabyToolTag::find()->all();
        foreach ($babyTag as $k => $v) {
            echo $v->id . $v->name . "\n";
            BabyGuide::updateAll(['period' => $v->id], 'tag = "' . $v->name . '"');
        }
        exit;
        $file = fopen('data.txt', 'r');
        $j = 1;
        while (($line = fgets($file)) !== false) {
            $i = 1;
            if ($j > 124) break;
            $j++;
            $rs1 = explode('||', trim($line));
            if ($rs1[1]) {
                $babyGuide = new BabyGuide();
                $babyGuide->sort = $i;
                $babyGuide->title = '孕期注意事项';
                $babyGuide->content = strip_tags($rs1[1]);
                $babyGuide->tag = $rs1[0];
                $babyGuide->save();
                $i++;
            }
            $rs2 = explode('|=|', $rs1[2]);
            $rs3 = explode('|=|', $rs1[3]);
            foreach ($rs2 as $k => $v) {
                $babyGuide = new BabyGuide();
                $babyGuide->sort = $i;
                $babyGuide->title = $v;
                $babyGuide->content = html_entity_decode(strip_tags($rs3[$k]));
                $babyGuide->tag = $rs1[0];

                $babyGuide->save();
                $i++;
            }
        }
    }


    public function userLogin($userid, $buserid)
    {
        //登录
        $userLogin = UserLogin::findAll(['userid' => $buserid]);
        foreach ($userLogin as $ulv) {
            echo "id:" . $ulv->id . "==";
            if ($ulv->phone || $ulv->openid || $ulv->xopenid || $ulv->unionid) {
                $or = ['or'];
                if ($ulv->phone) {
                    $or[] = ['phone' => $ulv->phone];
                }
                if ($ulv->openid) {
                    $or[] = ['openid' => $ulv->openid];
                }
                if ($ulv->xopenid) {
                    $or[] = ['xopenid' => $ulv->xopenid];
                }
                if ($ulv->unionid) {
                    $or[] = ['unionid' => $ulv->unionid];
                }

                $ul = UserLogin::find()
                    ->andFilterWhere(["userid" => $userid])
                    ->andFilterWhere($or)->one();
                if (!$ul) {
                    $ul = new UserLogin();
                    $ul->userid = $userid;
                    if ($ulv->password) $ul->password = $ulv->password;
                    if ($ulv->openid) $ul->openid = $ulv->openid;
                    if ($ulv->logintime) $ul->logintime = $ulv->logintime;
                    if ($ulv->xopenid) $ul->xopenid = $ulv->xopenid;
                    if ($ulv->unionid) $ul->unionid = $ulv->unionid;
                    if ($ulv->hxusername) $ul->hxusername = $ulv->hxusername;
                    if ($ulv->phone) $ul->phone = $ulv->phone;
                    if ($ulv->createtime) $ul->createtime = $ulv->createtime;
                    $ul->save();
                    echo "save==" . $ul->id;
                }

            }

        }
        ArticleComment::updateAll(['userid' => $userid], "userid=" . $buserid);
    }

    public function loginWeOpenid($bchildid)
    {
        //登录
        $userLogin = UserLogin::findAll(['userid' => $bchildid]);
        foreach ($userLogin as $ulv) {

            if ($ulv->openid || $ulv->xopenid || $ulv->unionid) {
                $weOpenid = WeOpenid::find()->andFilterWhere(['or', ['openid' => $ulv->openid], ['xopenid' => $ulv->xopenid], ['unionid' => $ulv->unionid]])->one();
                if ($weOpenid) {
                    $dp = DoctorParent::findOne(['parentid' => $bchildid]);
                    if (!$dp) {
                        $dp = new DoctorParent();
                    }
                    $dp->doctorid = $weOpenid->doctorid;
                    $dp->parentid = $bchildid;
                    $dp->level = $weOpenid->level;
                    $dp->createtime = $weOpenid->createtime;
                    $dp->save();
                    echo implode(',', $weOpenid->toArray());
                }
            }
        }
    }

    public function doctorParent($userid, $buserid)
    {
        $dp1 = DoctorParent::findOne(['parentid' => $buserid, 'level' => 1]);
        $dp = DoctorParent::findOne(['parentid' => $userid, 'level' => 1]);
        if ($dp) {
            echo "主已签约";
        }
        if (!$dp && $dp1) {
            $dp = new DoctorParent();
            $dp->doctorid = $dp1->doctorid;
            $dp->level = 1;
            $dp->createtime = $dp1->createtime;
            $dp->parentid = $userid;
            $dp->save();
            echo "dp update==";
        }
        if ($dp1) {
            DoctorParent::deleteAll('parentid =' . $buserid);
            echo "dp delete==";
        }
    }

    public function articleUser($userid, $buserid)
    {
        //修改宣教记录所属儿童
        $articleUser = ArticleUser::findAll(['touserid' => $buserid]);
        if ($articleUser) {
            foreach ($articleUser as $av) {
                $av->touserid = $userid;
                $av->save();
                echo "article:" . $av->artid;
            }
        } else {
            echo "article:无";
        }
    }

    public function child($userid, $buserid)
    {
        echo "==child:";
        echo ChildInfo::updateAll(['userid' => $userid], 'userid=' . $buserid);
    }

    /**
     * 清除重复记录
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDataa()
    {
        exit;
        $child = ChildInfo::find()
            ->select('count(*) as c,name,birthday,doctorid')
            // ->andFilterWhere(['doctorid'=>110555])
            ->groupBy('name,birthday,doctorid')->having(['>', 'c', 1])->all();
        foreach ($child as $k => $v) {
            echo "name:" . $v->name . "==";
            $childAll = ChildInfo::find()
                ->andWhere(['name' => $v->name])
                ->andWhere(['birthday' => $v->birthday])
                ->andWhere(['doctorid' => $v->doctorid])
                ->andWhere(['<', 'source', '39'])
                ->all();
            if ($childAll) {

                foreach ($childAll as $cv) {
                    echo "cv->id:" . $cv->id . "==";
                    echo "cv->userid:" . $cv->userid . "==";

                    $oldChild = ChildInfo::find()
                        ->andFilterWhere(['name' => $cv->name])
                        ->andFilterWhere(['birthday' => $cv->birthday])
                        ->andFilterWhere(['source' => $cv->doctorid])
                        ->andFilterWhere(['!=', 'id', $cv->id])
                        ->one();
//                    $oldChild = ChildInfo::find()
//                        ->andFilterWhere(['birthday' => $cv->birthday])
//                        ->andFilterWhere(['userid' => $cv->userid])
//                        ->andFilterWhere(['!=', 'id', $cv->id])
//                        ->one();
                    //var_dump($oldChild);
                    if ($oldChild) {
                        // echo implode(',', $oldChild->toArray()) . "\n";

                        $childid = $oldChild->id;
                        $userid = $oldChild->userid;
                        //$this->articleUser($childid, $userid, $cv->id);
                        // $this->doctorParent($userid, $cv->userid);
                        //$this->loginWeOpenid($cv->userid);
                        //$this->userLogin($userid,$cv->userid);
                        $user = User::findOne($cv->userid);
                        if ($user) {
                            $user->delete();
                        } else {
                            $cv->delete();
                        }

//                        //删除错误数据
//                        $cv->delete();

                    }
                }
            }
            echo "\n";
        }

    }

    /**
     * 清除重复记录
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionData()
    {
        exit;
        $field7 = ChildInfo::find()->select('field7')->andFilterWhere(['like', 'field7', 'E'])->column();
        $child = ChildInfo::find()
            ->select('count(*) as c,field7')
            ->andWhere(["!=", "field7", ""])
            ->andFilterWhere(['not in', 'field7', $field7])
            ->groupBy('field7')->having(['>', 'c', 1])->all();
        foreach ($child as $k => $v) {
            echo "field7:" . $v->field7 . "==";
            $childAll = ChildInfo::find()->andWhere(['field7' => $v->field7])->all();
            if ($childAll) {
                $childid = $childAll[0]->id;
                $userid = $childAll[0]->userid;
                echo "childid:" . $childid . "==";
                echo "userid:" . $userid . "==";

                foreach ($childAll as $ck => $cv) {
                    if ($ck == 0) {
                        continue;
                    }
                    echo "cv->id:" . $cv->id . "==";
                    echo "cv->userid:" . $cv->userid . "==";

                    $user = User::findOne($cv->userid);
                    if ($user) {
                        $user->delete();
                    } else {
                        $cv->delete();
                    }
//                    //登录
//                    $userLogin=UserLogin::findAll(['userid'=>$cv->userid]);
//                    foreach($userLogin as $ulv){
//                        echo "id:".$ulv->id."==";
//                        if($ulv->phone ||$ulv->openid || $ulv->xopenid || $ulv->unionid)
//                        {
//                            $or=['or'];
//                            if($ulv->phone){
//                                $or[]=['phone'=>$ulv->phone];
//                            }
//                            if($ulv->openid){
//                                $or[]=['openid'=>$ulv->openid];
//                            }
//                            if($ulv->xopenid){
//                                $or[]=['xopenid'=>$ulv->xopenid];
//                            }
//                            if($ulv->unionid){
//                                $or[]=['unionid'=>$ulv->unionid];
//                            }
//
//                            $ul=UserLogin::find()
//                                ->andFilterWhere(["userid"=>$userid])
//                                ->andFilterWhere($or)->one();
//                            if(!$ul)
//                            {
//                                $ul=new UserLogin();
//                                $ul->userid          =$userid;
//                                if($ulv->password)  $ul->password   =$ulv->password;
//                                if($ulv->openid)        $ul->openid   =$ulv->openid;
//                                if($ulv->logintime)     $ul->logintime   =$ulv->logintime;
//                                if($ulv->xopenid)       $ul->xopenid   =$ulv->xopenid;
//                                if($ulv->unionid)       $ul->unionid   =$ulv->unionid;
//                                if($ulv->hxusername)    $ul->hxusername   =$ulv->hxusername;
//                                if($ulv->phone)         $ul->phone   =$ulv->phone;
//                                if($ulv->createtime) $ul->createtime   =$ulv->createtime;
//                                $ul->save();
//                                echo "save==".$ul->id;
//                            }
//
//                        }
//
//                    }
//                   ArticleComment::updateAll(['userid' => $userid], "userid=" . $cv->userid);


//                    //登录
//                    $userLogin=UserLogin::findAll(['userid'=>$cv->userid]);
//                    foreach($userLogin as $ulv){
//
//                        if($ulv->openid || $ulv->xopenid || $ulv->unionid)
//                        {
//                            $weOpenid=WeOpenid::find()->andFilterWhere(['or',['openid'=>$ulv->openid],['xopenid'=>$ulv->xopenid],['unionid'=>$ulv->unionid]])->one();
//                            if($weOpenid)
//                            {
//                                $dp=DoctorParent::findOne(['parentid'=>$cv->userid]);
//                                if(!$dp)
//                                {
//                                    $dp=new DoctorParent();
//                                }
//                                $dp->doctorid=$weOpenid->doctorid;
//                                $dp->parentid=$cv->userid;
//                                $dp->level=$weOpenid->level;
//                                $dp->createtime=$weOpenid->createtime;
//                                $dp->save();
//                                var_dump($dp->errors);
//
//                                echo implode(',',$weOpenid->toArray());
//                                echo "\n";
//                            }
//                        }
//                    }


//                    $dp1 = DoctorParent::findOne(['parentid' => $cv->userid, 'level' => 1]);
//                    $dp = DoctorParent::findOne(['parentid' => $userid, 'level' => 1]);
//                    if (!$dp && $dp1) {
//                        $dp=new DoctorParent();
//                        $dp->doctorid = $dp1->doctorid;
//                        $dp->level = 1;
//                        $dp->createtime=$dp1->createtime;
//                        $dp->parentid=$userid;
//                        $dp->save();
//                        echo "dp update==";
//                    } else {
//                        echo "dp delete==";
//                        DoctorParent::deleteAll('parentid =' . $cv->userid);
//                    }
                    //修改宣教记录所属儿童
//                    $articleUser=ArticleUser::findAll(['childid'=>$cv->id]);
//                    if($articleUser){
//                        foreach($articleUser as $av) {
//                            echo "artid1:".$av->id."==";
//                            $au = ArticleUser::find()->andWhere(['childid' => $childid])
//                                ->andFilterWhere(['artid' => $av->artid])->one();
//                            echo "artid2:".$au->id."==";
//                            if (!$au) {
//                                echo "update==";
//                                $av->touserid=$userid;
//                                $av->childid=$childid;
//                                $av->save();
//                            }else{
//                                echo "delete==";
//                                $av->delete();
//                            }
//                        }
//                    }

//                    ArticleUser::updateAll(['childid' => $childid, 'userid' => $userid], 'childid =' . $cv->id);
//                    DoctorParent::updateAll(['parentid' => $userid], 'parentid =' . $cv->userid);
//                    ArticleComment::updateAll(['userid' => $userid], "userid=" . $cv->userid);
//                    UserLogin::updateAll(['userid' => $userid], "userid=" . $cv->userid);
                }
            }
            echo "\n";
        }

    }


    //禁用危险
    public function actionName()
    {
        exit;
        $childInfo = ChildInfo::find()->andFilterWhere(['source' => 0])->andFilterWhere(['id' => 60413])->all();
        foreach ($childInfo as $k => $v) {
            //var_dump($v->toArray());
            $child = ChildInfo::find()
                ->andFilterWhere(['child_info.name' => $v->name])
                ->andWhere(['>', 'child_info.source', 0])
                //->andWhere(['child_info.source'=>$v->doctorid])
                ->andFilterWhere(['child_info.birthday' => $v->birthday])
                ->andFilterWhere(['child_info.gender' => $v->gender])
                ->andFilterWhere(['!=', 'child_info.userid', $v->userid])
                ->one();
            //var_dump($child);exit;
            $doctorP = DoctorParent::findOne(['parentid' => $child->userid]);

            if ($child && $doctorP->level != 1) {
                echo implode(',', $v->toArray());
                echo "\n";
                echo implode(',', $child->toArray());
                echo "\n";
                echo implode(',', User::findOne($v->userid)->toArray());
                echo "\n";
                echo implode(',', User::findOne($v->userid)->toArray());
                echo "\n=======================";


                $vuserid = $v->userid;
                $cuserid = $child->userid;

                var_dump($vuserid);
                var_dump($cuserid);
                $userParent = UserParent::findOne(['userid' => $cuserid]);
                $userParent1 = UserParent::findOne(['userid' => $vuserid]);


                if ($userParent && $userParent1) {
                    $userParent->userid = 0;
                    $userParent->save();

                    $child->userid = $vuserid;
                    $child->save();


                    $userParent1->userid = $cuserid;
                    $userParent1->save();


                    $userParent->userid = $vuserid;
                    $userParent->save();

                    $v->userid = $cuserid;
                    $v->save();
                    echo "====end";
                    exit;
                    echo "\n";
                }
            }
        }
        // echo $childInfo->count();exit;
    }

    public function actionEbb()
    {

        echo date('Y-m-d\TH:i:s\Z', time() + (3600 * 24));
        exit;

        $childs = ChildInfo::find()->andFilterWhere(['doctorid' => 110555])->all();
        foreach ($childs as $k => $v) {
            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid, 'level' => 1]);
            if (!$doctorParent) {
                $v->doctorid = $v->source;
                $v->save();
                echo $v->userid;
                echo "\n";
            }
        }
        exit;
        $doctorParent = DoctorParent::find()->andFilterWhere(['level' => 1])->andFilterWhere(['doctorid' => 47156])->all();

//        foreach ($doctorParent as $k => $v) {
//            $child = ChildInfo::findOne(['userid' => $v->parentid]);
//
//            if ($child) {
//                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
//
//                $child->doctorid = $doctor->hospitalid;
//                $child->save();
//                echo $child->userid;
//                echo "\n";
//            }
//
//        }
//        exit;

        foreach ($doctorParent as $k => $v) {
            echo $v->parentid . "===";
            $userParent = UserParent::findOne(['userid' => $v->parentid]);
            if ($userParent->source > 38) {
                $doctor = UserDoctor::findOne(['hospitalid' => $userParent->source]);
                if ($doctor) {
                    echo $doctor->userid;
                    $v->doctorid = $doctor->userid;
                    $v->save();
                }
            }
            echo "\n";
        }
        exit;
        $user = User::find()->where(['source' => 1])->all();
        foreach ($user as $k => $v) {
            $doctorParent = DoctorParent::find()->andFilterWhere(['parentid' => $v->id])->one();
            if (!$doctorParent or $doctorParent->level != 1) {
                $hospitalid = 110565;
                $doctorid = 47156;
                $userParent = UserParent::findOne(['userid' => $v->id]);
                if ($userParent->source > 38) {
                    $doctor = UserDoctor::findOne(['hospitalid' => $userParent->source]);
                    $doctorid = $doctor ? $doctor->userid : 47156;
                    $hospitalid = $doctor ? $doctor->hospitalid : 110565;
                }

                echo $v->id . "==";
                $doctorParent = DoctorParent::findOne(['parentid' => $v->id]);
                $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                $doctorParent->doctorid = $doctorid ? $doctorid : 47156;
                $doctorParent->parentid = $v->id;
                $doctorParent->level = 1;
                echo $doctorParent->doctorid . "==";
                $doctorParent->save();
                echo $hospitalid . "==";

                ChildInfo::updateAll(['doctorid' => $hospitalid], 'userid=' . $v->id);
            }
            echo "\n";
        }
        exit;
    }

    public function actionDoctoridn()
    {
        ini_set('memory_limit', '1024M');
        $doctorParent = DoctorParent::find()->where(['level' => 1])->orderBy('createtime desc')->all();
        foreach ($doctorParent as $k => $v) {
            $userDoctor = UserDoctor::findOne(['userid' => $v->doctorid]);
            if ($userDoctor) {
                $hospital = $userDoctor->hospitalid;
                ChildInfo::updateAll(['doctorid' => $hospital], 'userid=' . $v->parentid);
                echo $v->doctorid . "==";
                echo $v->parentid . "==";
                echo $hospital;
            }
            echo "\n";
        }
    }

    public function actionDoctorid()
    {
        ini_set('memory_limit', '1024M');

        $child = ChildInfo::find()->andFilterWhere(['source' => 110559])->all();
        foreach ($child as $k => $v) {
            echo $v->id . "==";
            echo $v->source;
            $v->doctorid = $v->source;
            $v->save();
            echo "\n";
        }
    }

    public function actionArc()
    {

        $user = User::find()
            ->andFilterWhere(['`user`.source' => 1])
            ->leftJoin('child_info', '`child_info`.`userid` = `user`.`id`')
            ->andWhere(['!=', '`child_info`.`userid`', '']);
        $i = 0;
        foreach ($user->all() as $k => $v) {
            echo $v->id . "==";
            $childInfo = ChildInfo::findOne(['userid' => $v->id]);
            $child = ChildInfo::find()->andFilterWhere(['name' => $childInfo->name])->andFilterWhere(['birthday' => $childInfo->birthday])
                ->andFilterWhere(['gender' => $childInfo->gender])->andFilterWhere(['>', 'source', 38])->one();
            if ($child) {
                echo $child->name;
                $i++;
            }
            echo "\n";
        }
        var_dump($i);
        exit;


    }

    public function actionArea()
    {
        ini_set('memory_limit', '1024M');
        $child = ChildInfo::find()->andFilterWhere(['>', 'source', 38])->all();
        foreach ($child as $k => $v) {
            echo "userid=>" . $v->userid;
            $doctor = UserDoctor::findOne(['hospitalid' => $v->source]);
            if ($doctor) {
                echo ",doctorid=>" . $doctor->userid;

                $userParent = UserParent::findOne(['userid' => $v->userid]);
                if ($userParent) {
                    $userParent->province = $doctor->province;
                    $userParent->city = $doctor->city;
                    $userParent->county = $doctor->county;
                    echo ",county=>" . $userParent->county;
                    $userParent->save();
                }
            }
            echo "\n";
        }
    }

    public function actionUrlPush()
    {
//        $data = [
//            'first' => array('value' => "参与社区儿童中医药健康指导服务调查问卷，必得现金红包，先到先得\n",),
//            'keyword1' => ARRAY('value' => "2018-05-20"),
//            'keyword2' => ARRAY('value' => "为了更好的服务每一个家庭，请参与我们社区中医健康指导服务的问卷调查，希望各位家长抽出宝贵时间支持我们的工作"),
//            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
//        ];

//        $data = [
//            'first' => array('value' => "您好，为确保享受体检通知服务,请尽快完善宝宝信息\n",),
//            'keyword1' => ARRAY('value' => "宝宝基本信息"),
//            'keyword2' => ARRAY('value' => "广外社区区卫生服务中心"),
//            'remark' => ARRAY('value' => "\n 点击完善宝宝信息", 'color' => '#221d95'),
//        ];
//
//        $rs = WechatSendTmp::send($data, 'o5ODa0wc1u3Ihu5WvCVqoACeQ-HA', 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', 'https://jinshuju.net/f/Sfodlu');
//        exit;


        $data = [
            'first' => array('value' => "您好，为确保享受体检通知服务,请尽快完善宝宝信息\n",),
            'keyword1' => ARRAY('value' => "宝宝基本信息"),
            'keyword2' => ARRAY('value' => "为了更好的服务每一个家庭，请参与我们社区中医健康指导服务的问卷调查，希望各位家长抽出宝贵时间支持我们的工作"),
            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
//
        $rs = [];
        $file = fopen("openid2.log", 'r');
        while (($line = fgets($file)) !== false) {
            $row = explode(',', trim($line));
            $openid = $row[0];
            $doctor = $row[1];
            $rs[$openid] = $doctor;
        }
//        $openidl=[];
//        $file1=fopen("openidl",'r');
//        while(($line1=fgets($file1))!==false){
//            $rsa=trim($line1);
//            if(!in_array($rs,$openidl))
//            {
//                $openidl[]=$rsa;
//            }
//        }
//        foreach($rs as $k=>$v){
//            if(!in_array($k,$openidl))
//            {
//                echo $k.",".$v."\n";
//            }
//        }
//        exit;


        foreach ($rs as $k => $v) {

            $data = [
                'first' => array('value' => "您好，为确保享受儿童中医健康指导服务,请尽快完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => $v),
                'remark' => ARRAY('value' => "\n 点击完善宝宝信息", 'color' => '#221d95'),
            ];
            $rs = WechatSendTmp::send($data, $k, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
            echo $k . "\n";
        }
        exit;

        $weOpenid = WeOpenid::find()->andWhere(['level' => 0])->all();
        foreach ($weOpenid as $k => $v) {
            if ($v->openid) {
                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                $hospital = Hospital::findOne($doctor->hospitalid);
                echo $v->openid . "," . $hospital->name . "\n";
            }
        }

        $doctorParent = DoctorParent::findAll(['level' => 1]);
        foreach ($doctorParent as $k => $v) {

            if (!ChildInfo::findOne(['userid' => $v->parentid])) {
                $userLogin = UserLogin::findOne(['userid' => $v->parentid]);
                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                $hospital = Hospital::findOne($doctor->hospitalid);
                if ($userLogin && $userLogin->openid) {
                    echo $userLogin->openid . "," . $hospital->name;
                    echo "\n";
                }
            }
        }
        exit;


        foreach ($weOpenid as $k => $v) {
            $data = [
                'first' => array('value' => "您好，为确保享受体检通知服务,请尽快完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => "为了更好的服务每一个家庭，请参与我们社区中医健康指导服务的问卷调查，希望各位家长抽出宝贵时间支持我们的工作"),
                'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
            ];
        }


        $userids = UserLogin::find()->where(['!=', 'openid', ''])->all();
        foreach ($userids as $k => $v) {
            echo $v->userid . "==";
            //$userLogin=UserLogin::findOne(['userid'=>$v->parentid]);
            $userLogin = $v;
            if ($userLogin->openid) {
                $rs = WechatSendTmp::send($data, 'o5ODa0wc1u3Ihu5WvCVqoACeQ-HA', '﻿wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', 'https://jinshuju.net/f/Sfodlu');
                echo $rs;
            }
            echo "\n";
        }

    }

    public function actionArticlePush()
    {
        $article = \common\models\Article::findOne(323);

//        $data = [
//            'first' => array('value' => $article->info->title . "\n",),
//            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
//            'keyword2' => ARRAY('value' => strip_tags($article->info->content)),
//            'remark' => ARRAY('value' => "\n 点击查看社区卫生服务中心通知详情", 'color' => '#221d95'),
//        ];
//        $miniprogram = [
//            "appid" => \Yii::$app->params['wxXAppId'],
//            "pagepath" => "/pages/article/view/index?id=" . $article->id,
//        ];


        $data = [
            'first' => array('value' => $article->info->title . "\n",),
            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
            'keyword2' => ARRAY('value' => '儿宝宝'),
            'keyword3' => ARRAY('value' => '儿宝宝'),
            'keyword4' => ARRAY('value' => '宝爸宝妈'),
            'keyword5' => ARRAY('value' => $article->info->title),

            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=" . $article->id,
        ];
        //$userids=UserLogin::find()->where(['userid'=>'47388'])->all();

        //$userids = DoctorParent::find()->andFilterWhere(['parentid' => 77107])->all();


        $userids = WeOpenid::find()->andFilterWhere([">", 'createtime', '1529942400'])
            ->andFilterWhere(['level' => 0])
            ->andWhere(['!=', 'openid', ''])
            ->all();
        $openids = [];
        if ($article) {
            foreach ($userids as $k => $v) {
                //$userLogin = UserLogin::findOne(['userid' => $v->userid]);
                //$userLogin=$v;
                if (in_array($v->openid, $openids))
                    $openids[] = $v->openid;
                if ($v->openid) {
                    $rs = WechatSendTmp::send($data, $v->openid, \Yii::$app->params['zhidao'], '', $miniprogram);
                    echo $rs;
                }
                if ($article->art_type != 2) {
                    $key = $article->catid == 6 ? 3 : 5;
                    //Notice::setList($v->userid, $key, ['title' => $article->info->title, 'ftitle' => date('Y年m月d H:i'), 'id' => "/article/view/index?id=" . $article->id,]);
                }
                echo "\n";
            }
        }
    }

    public function actionTe()
    {
        $weOpenid = WeOpenid::find()->andFilterWhere(['level' => 1])->andFilterWhere(['>', 'createtime', '1524067200'])->all();
        foreach ($weOpenid as $k => $v) {
            $user = UserLogin::findOne(['openid' => $v->openid]);
            if ($user) {
                $parentid = $user->userid;
                $doctorParent = DoctorParent::findOne(['parentid' => $parentid]);
                if ($doctorParent) {
                    $doctorParent->parentid = $parentid;
                    $doctorParent->doctorid = $v->doctorid;
                    $doctorParent->createtime = $v->createtime;
                    $doctorParent->level = 1;
                    $doctorParent->save();
                    echo $parentid;
                }
            }
        }
        exit;
    }

    public function actionText()
    {


        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=139.129.246.51;dbname=child_health',
            'username' => 'wedoctors_admin',
            'password' => 'trd7V37v3PXeU9vn',
        ]);
        $connection->open();

        $f = fopen("data/doctor_parent.sql", 'r');
        $i = 0;
        while (($line = fgets($f)) !== false) {
            echo $line;
            $command = $connection->createCommand(trim($line));
            $command->execute();
            echo "\n";
            //var_dump($post);exit;
        }
        exit;
    }


    public function actionBd()
    {

        echo md5(md5("139110083832QH@6%3(87"));
        exit;

        for ($i = 1; $i < 10; $i++) {
            echo $i . "\n";
            $row = ChildInfo::getChildType($i);
            echo date('Y-m-d', $row['firstday']) . "\n";
            echo date('Y-m-d', $row['lastday']) . "\n";
        }
        exit;


        $text = [
            '2018-01-01',
            '2019-02-01',
            '2018-12-01',
            '2017-01-01'
        ];
        rsort($text);
        var_dump($text);
        exit;

        $curl = new HttpRequest('https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eptlQANnGdZaa0B61xqymbGJib67XqeOEufjIeUXXUx9CibrrAkic1JichlNr698cbfN7u8IEsGJEVic9g/0', true, 2);
        echo $curl->get();
        exit;
        ini_set('memory_limit', '2048M');

        $child = ChildInfo::find()->all();
        foreach ($child as $k => $v) {
            $v->birthday = strtotime(date('Y-m-d', $v->birthday));
            $v->save();
            echo date('Y-m-d H:i:s', $v->birthday);
            echo "\n";
        }
    }

    /**
     * 体检数据
     */
    public function actionEx()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $file_list = glob("data/ex/*.csv");
        foreach ($file_list as $fk => $fv) {
            preg_match("#\d+#", $fv, $m);
            $hospitalid = substr($m[0], 0, 6);
            echo $hospitalid . "\n";
            $f = fopen($fv, 'r');
            $i = 0;
            while (($line = fgetcsv($f)) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $i++;
                echo $hospitalid . "=" . $i . "===";
                $row = $line;

                if ($row[3] < '2018-01-01 00:00:00') {
                    echo "end\n";
                    break;
                }
                $row[3] = substr($row[3], 0, strlen($row[3]) - 11);
                $ex = Examination::find()->andFilterWhere(['field1' => $row[0]])
                    ->andFilterWhere(['field2' => $row[1]])
                    ->andFilterWhere(['field3' => $row[2]])
                    ->andFilterWhere(['field4' => $row[3]])
                    ->andFilterWhere(['source' => $hospitalid])
                    ->andFilterWhere(['field19' => $row[18]])->one();
                if ($ex) {
                    echo "jump\n";
                    continue;
                }
                $ex = $ex ? $ex : new Examination();

                $child = ChildInfo::find()->andFilterWhere(['name' => trim($row[0])])
                    ->andFilterWhere(['birthday' => strtotime($row[18])])
                    ->andFilterWhere(['source' => $hospitalid])
                    ->one();
                echo $row[0];

                $childData = [

                    'field1' => $row[0],
                    'field2' => $row[1],
                    'field3' => $row[2],
                    'field4' => $row[3],
                    'field5' => $row[4],
                    'field6' => $row[5],
                    'field7' => $row[6],
                    'field8' => $row[7],
                    'field9' => $row[8],
                    'field10' => $row[9],
                    'field11' => $row[10],
                    'field12' => $row[11],
                    'field13' => $row[12],
                    'field14' => $row[13],
                    'field15' => $row[14],
                    'field16' => $row[15],
                    'field17' => $row[16],
                    'field18' => $row[17],
                    'field19' => $row[18],
                    'field20' => $row[19],
                    'field21' => $row[20],
                    'field22' => $row[21],
                    'field23' => $row[22],
                    'field24' => $row[23],
                    'field25' => $row[24],
                    'field26' => $row[25],
                    'field27' => $row[26],
                    'field28' => $row[27],
                    'field29' => $row[28],
                    'field30' => $row[29],
                    'field31' => $row[30],
                    'field32' => $row[31],
                    'field33' => $row[32],
                    'field34' => $row[33],
                    'field35' => $row[34],
                    'field36' => $row[35],
                    'field37' => $row[36],
                    'field38' => $row[37],
                    'field39' => $row[38],
                    'field40' => $row[39],
                    'field41' => $row[40],
                    'field42' => $row[41],
                    'field43' => $row[42],
                    'field44' => $row[43],
                    'field45' => $row[44],
                    'field46' => $row[45],
                    'field47' => $row[46],
                    'field48' => $row[47],
                    'field49' => $row[48],
                    'field50' => $row[49],
                    'field51' => $row[50],
                    'field52' => $row[51],
                    'field53' => $row[52],
                    'field54' => $row[53],
                    'field55' => $row[54],
                    'field56' => $row[55],
                    'field57' => $row[56],
                    'field58' => $row[57],
                    'field59' => $row[58],
                    'field60' => $row[59],
                    'field61' => $row[60],
                    'field62' => $row[61],
                    'field63' => $row[62],
                    'field64' => $row[63],
                    'field65' => $row[64],
                    'field66' => $row[65],
                    'field67' => $row[66],
                    'field68' => $row[67],
                    'field69' => $row[68],
                    'field70' => $row[69],
                    'field71' => $row[70],
                    'field72' => $row[71],
                    'field73' => $row[72],
                    'field74' => $row[73],
                    'field75' => $row[74],
                    'field76' => $row[75],
                    'field77' => $row[76],
                    'field78' => $row[77],
                    'field79' => $row[78],
                    'field80' => $row[79],
                    'field81' => $row[80],
                    'field82' => $row[81],
                    'field83' => $row[82],
                    'field84' => $row[83],
                    'field85' => $row[84],
                    'field86' => $row[85],
                    'field87' => $row[86],
                    'field88' => $row[87],
                    'field89' => $row[88],
                    'field90' => $row[89],
                    'field91' => $row[90],
                    'field92' => $row[91],
                    'source' => $hospitalid,
                ];

                if (!$child) {
                    echo "--儿童不存在";
                    // $childData['childid'] = 0;
                } else {
                    echo "--儿童存在";
                    $childData['childid'] = $child->id;
                }


//                $childData = array_filter($childData, function ($e) {
//                    if ($e != '' || $e != null) return true;
//                    return false;
//                });
                foreach ($childData as $k => $v) {
                    $ex->$k = $v;
                }
                $ex->save();
                if ($ex->firstErrors) {
                    echo "error";
                    var_dump($ex->firstErrors);
                }
                echo "\n";
            }
        }
    }

    /**
     * 体检更新提醒
     */
    public function actionExUpdate()
    {
        $logins = [];
        $i = 0;
        ini_set('memory_limit', '1024M');
        $ex = Examination::find()->andFilterWhere(['isupdate' => 1])->andFilterWhere(['>', 'field4', '2018-12-13'])->groupBy('childid')->all();


        foreach ($ex as $k => $v) {
            $child = ChildInfo::findOne(['id' => $v->childid]);
            if ($child) {
                //echo $child->id . "===$k" . "===";
                $login = $child->login;
                if ($login->openid && !in_array($login->openid, $logins)) {
                    $logins[] = $login->openid;
                    $data = [
                        'first' => array('value' => "您好，宝宝近期的体检结果已更新\n",),
                        'keyword1' => ARRAY('value' => $child->name),
                        'keyword2' => ARRAY('value' => "身高:{$v->field40},体重:{$v->field70},头围:{$v->field80}"),
                        'keyword3' => ARRAY('value' => $v->field9),
                        'keyword4' => ARRAY('value' => $v->field4),
                        'remark' => ARRAY('value' => "\n 点击可查看本体检报告的详细内容信息", 'color' => '#221d95'),
                    ];
                    $miniprogram = [
                        "appid" => \Yii::$app->params['wxXAppId'],
                        "pagepath" => "/pages/user/examination/index?id=" . $child->id,
                    ];
                    $rs = WechatSendTmp::send($data, $login->openid, \Yii::$app->params['tijian'], '', $miniprogram);
                    echo $child->userid . "======";
                    echo $login->openid . "======";

                    echo json_encode($rs);
                    echo "\n";
                    //小程序首页通知
                    Notice::setList($login->userid, 1, ['title' => "宝宝近期的体检结果已更新", 'ftitle' => "点击可查看本体检报告的详细内容信息", 'id' => "/user/examination/index?id=" . $child->id,], "id=" . $child->id);
                    $i++;
                }
            }
            $v->isupdate = 0;
            $v->save();
        }
        echo $i;
        Examination::updateAll(['isupdate' => 0]);
        echo "true\n";
    }

    /**
     * 更新用户unionid
     * @throws \yii\web\HttpException
     */
    public function actionGetUid()
    {
        $wechat = new MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['AppID'],
            'appSecret' => \Yii::$app->params['AppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey']
        ]);
        $access_token = $wechat->getAccessToken();

        $weOpenid = WeOpenid::find()->andWhere(['unionid' => ''])->andWhere(['!=', 'openid', ''])->all();
        foreach ($weOpenid as $k => $v) {

            $path = '/cgi-bin/user/info?access_token=' . $access_token . "&openid=" . $v->openid . "&lang=zh_CN";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 2);
            $userJson = $curl->get();
            $userInfo = json_decode($userJson, true);
            if ($userInfo['unionid']) {
                $v->unionid = $userInfo['unionid'];
                $v->save();
                echo "成功\n";
            } else {
                echo "失败\n";
            }
        }
        exit;


        $user = UserLogin::find()->where(['!=', 'openid', ''])->andWhere(["=", 'unionid', ''])->orderBy('userid desc')->all();
        foreach ($user as $k => $v) {
            $userLogin = UserLogin::findOne(['userid' => $v->userid]);
            $openid = $userLogin->openid;
            $path = '/cgi-bin/user/info?access_token=' . $access_token . "&openid=" . $openid . "&lang=zh_CN";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 2);
            $userJson = $curl->get();
            $userInfo = json_decode($userJson, true);
            var_dump($userInfo);
            exit;
            if ($userInfo['unionid']) {
                $userLogin->unionid = $userInfo['unionid'];
                $userLogin->save();
                echo $v->userid . "成功";
            } else {
                echo $v->userid . "没有";

            }
            echo "\n";
        }

    }

    public function actionSet()
    {
        ini_set('memory_limit', '1024M');

        $userParent = UserParent::find()->andFilterWhere(['>', 'source', 38])->all();
        foreach ($userParent as $k => $v) {
            echo "parentid=" . $v->userid . ",";
            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid]);
            if (!$doctorParent) {
                $userid = UserDoctor::findOne(['hospitalid' => $v->source])->userid;
                echo "doctorid=" . $userid . ",";
                if ($userid) {

                    $doctorP = new DoctorParent();
                    $doctorP->doctorid = $userid;
                    $doctorP->parentid = $v->userid;
                    $doctorP->level = -1;
                    if ($doctorP->save()) {
                        echo "成功\n";
                    } else {
                        var_dump($doctorP->firstErrors);
                        echo "\n";
                    }
                    continue;
                }
            }
            echo "失败\n";
        }


    }

    /**
     * 妇幼二期数据
     */
    public function actionGet()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        ini_set("max_execution_time", "0");
        set_time_limit(0);

        $file_list = glob("data/*.csv");
        foreach ($file_list as $fk => $fv) {
            preg_match("#\d+#", $fv, $m);
            $hospitalid = $m[0];
            if ($hospitalid) {
                $f = fopen($fv, 'r');
                $i = 0;
                while (($line = fgets($f)) !== false) {
                    echo $i . "===";
                    $i++;
                    $row = explode(",", trim($line));
                    if (strlen($row[31]) < 11 && strlen($row[35]) < 11 && strlen($row[12]) < 11) {
                        echo "--31-" . $row['31'];
                        echo "--35-" . $row['35'];
                        echo "--12-" . $row['12'];

                        echo "无手机号\n";
                        continue;
                    }


                    if (strlen($row[31]) == 11) {
                        $phone = $row[31];
                    } elseif (strlen($row[12]) == 11) {
                        $phone = $row[12];
                    } elseif (strlen($row[35]) == 11) {
                        $phone = $row[35];
                    }

                    if (!$phone || strlen($phone) != 11) {
                        echo "手机号不合法\n";
                        continue;
                    }
                    $user = User::findOne(['phone' => $phone]);
                    $user = $user ? $user : new User();
                    $user->phone = $phone;
                    $user->source = 2;
                    $user->type = 1;
                    echo $user->id . "====";
                    if ($user->save()) {
                        $login = UserLogin::findOne(['userid' => $user->id]);
                        $login = $login ? $login : new UserLogin();
                        $login->userid = $user->id;
                        $login->password = md5(md5($user->phone . "2QH@6%3(87"));
                        $login->save();
                        $userparent = UserParent::findOne(['userid' => $user->id]);
                        $userparent = $userparent ? $userparent : new UserParent();
                        echo $row[8] . "====";

                        $userparent->userid = $user->id;
                        $userparent->mother = $row[8];
                        $userparent->mother_phone = intval($row[31]);
                        $userparent->father_phone = intval($row[35]);

                        $userparent->father = $row[10];
                        $userparent->mother_id = $row[9];
                        $userparent->father_birthday = strtotime($row[32]);
                        $userparent->address = $row[36];
                        $userparent->source = $hospitalid;
                        $userparent->field1 = $row[1];
                        $userparent->field34 = $row[34];
                        $userparent->field33 = $row[33];
                        $userparent->field30 = $row[30];
                        $userparent->field29 = $row[29];
                        $userparent->field28 = $row[28];
                        $userparent->field12 = intval($row[12]);
                        $userparent->field11 = $row[11];
                        if ($userparent->save()) {
                            $child = ChildInfo::findOne(['name' => $row[3], 'userid' => $user->id]);
                            $child = $child ? $child : new ChildInfo();
                            $child->userid = $user->id;
                            $child->name = $row[3];
                            $child->gender = $row[4] == "男" ? 1 : 2;
                            $child->birthday = intval(strtotime($row[5]));
                            $child->createtime = time();
                            $child->source = $hospitalid;
                            $child->doctorid = $hospitalid;
                            $child->field54 = $row[54];
                            $child->field53 = $row[53];
                            $child->field52 = $row[52];
                            $child->field51 = $row[51];
                            $child->field50 = $row[50];
                            $child->field49 = $row[49];
                            $child->field48 = $row[48];
                            $child->field47 = $row[47];
                            $child->field46 = $row[46];
                            $child->field45 = $row[45];
                            $child->field44 = $row[44];
                            $child->field43 = $row[43];
                            $child->field42 = $row[42];
                            $child->field41 = $row[41];
                            $child->field40 = $row[40];
                            $child->field39 = $row[39];
                            $child->field38 = $row[38];
                            $child->field37 = $row[37];
                            $child->field27 = $row[27];
                            $child->field26 = $row[26];
                            $child->field25 = $row[25];
                            $child->field24 = $row[24];
                            $child->field23 = $row[23];
                            $child->field22 = $row[22];
                            $child->field21 = $row[21];
                            $child->field20 = $row[20];
                            $child->field19 = $row[19];
                            $child->field18 = $row[18];
                            $child->field17 = $row[17];
                            $child->field16 = $row[16];
                            $child->field15 = $row[15];
                            $child->field14 = $row[14];
                            $child->field13 = $row[13];
                            $child->field7 = $row[7];
                            $child->field6 = $row[6];
                            $child->field0 = $row[0];
                            if ($child->save()) {
                                echo "成功\n";
                                continue;
                            }
                            var_dump($child->firstErrors);
                        }
                        var_dump($userparent->firstErrors);

                    }
                    var_dump($user->firstErrors);
                }
                echo "失败\n";
            }
        }
    }


    public function actionTest()
    {
//        $list = DoctorParent::find()->andFilterWhere(['level'=>1])->andFilterWhere(['doctorid'=>0])->all();
//        foreach($list as $k=>$v)
//        {
//            echo $v->parentid;
////            $doctorParent=DoctorParent::find()->where(['>','doctorid',0])->andFilterWhere(['parentid'=>$v->parentid])->all();
////            if(count($doctorParent)==1)
////            {
////                $v->doctorid=$doctorParent[0]->doctorid;
////                $v->save();
////                $doctorParent[0]->delete();
////                echo "==del";
////            }
//            $childInfo = ChildInfo::findOne(['userid'=>$v->parentid]);
//            if($childInfo->source)
//            {
//                $doctor=UserDoctor::findOne(['hospitalid'=>$childInfo->source]);
//                if($doctor) {
//                    $v->doctorid =$doctor->userid;
//                    $v->save();
//                }
//                if($childInfo->source==38)
//                {
//                    $v->doctorid =38;
//                    $v->save();
//                }
//
//            }else{
//                $v->doctorid =47156;
//                $v->save();
//            }
//            echo "\n";
//        }


        $return = \Yii::$app->beanstalk
            ->putInTube('export', ['artid' => 301, 'userids' => [49106]]);
        var_dump($return);
        exit;

        //ChatRecord::updateAll(['read'=>1],['touserid'=>18486,'userid'=>4146]);
    }

}