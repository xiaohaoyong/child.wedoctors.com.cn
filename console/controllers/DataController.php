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
use common\models\AppointOrder;
use common\models\AppointOrder1;
use common\models\Area;
use common\models\Article;
use common\models\ArticleComment;
use common\models\ArticlePushVaccine;
use common\models\ArticleUser;
use common\models\Autograph;
use common\models\BabyGuide;
use common\models\BabyTool;
use common\models\BabyToolTag;
use common\models\ChatRecord;
use common\models\ChildInfo;
use common\models\DataUpdateRecord;
use common\models\DataUser;
use common\models\DoctorHospital;
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
use common\models\Test1;
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
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


class DataController extends \yii\console\Controller
{
    public function actionTesta($num=1)
    {
//        $doctorParent=DoctorParent::findAll(['doctorid'=>400564]);
//        foreach($doctorParent as $k=>$v) {
//            $openid = UserLogin::getOpenid($v->parentid);
//            $child=ChildInfo::find()->where(['userid'=>$v->parentid])->andWhere(['>','userid',405669])->andWhere(['field27'=>''])->andWhere(['idcard'=>''])->one();
//
//            echo $v->parentid;
//            if($openid && $child) {
//                echo "f";
//                $data = [
//                    'first' => array('value' => '八里庄社区卫生服务中心提醒您完善宝宝信息'),
//                    'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
//                    'keyword2' => ARRAY('value' => '各位家长：为了更好的为签约儿童提供签约管理服务，现需要各位家长完善儿童身份证号码或儿童医学编码信息（六个月以下，没有身份证号的儿童），请各位家长按照以下说明完善信息即可，感谢您的支持。八里庄社区卫生服务中心预防保健科'),
//                    'remark' => ARRAY('value' => "点击查看信息完善说明！", 'color' => '#221d95')
//                ];
//
//                $miniprogram = [
//                    "appid" => \Yii::$app->params['wxXAppId'],
//                    "pagepath" => "/pages/article/view/index?id=1484",
//                ];
//                WechatSendTmp::send($data, $openid, 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE', '', $miniprogram);
//            }
//            echo "\n";
//
//        }
//exit;
        ini_set('memory_limit', '6000M');
        $totle=282750;
        $limit=ceil($totle/50);
        $snum=$num*$limit;

        $login=UserLogin::find()->select('openid')->where(['!=','openid',''])->groupBy('openid')->offset($snum)->limit($limit)->column();
        foreach($login as $k=>$v){
            echo $v."\n";
            $data = [
                'first' => ['value' => '在生完孩子后，总感觉下身有下坠感，有时咳嗽、大笑、运动、抱重物还漏尿，松松的肚子总下不去，或是稍微累一点就腰酸背痛。很多宝妈表示自己都遇到过这种情况，有时简直太尴尬了。本期杨主任为各位宝妈讲解盆底康复是什么'],
                'keyword1' => ARRAY('value' => '杨主任为您揭秘产后盆底会有什么变化？第十七期健康直播课即将开始'),
                'keyword2' => ARRAY('value' => '2020年12月06日下午3点'),
                'remark' => ARRAY('value' => ""),
            ];
            //$rs = WechatSendTmp::send($data,'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v1/course/alive/l_5fcc3594e4b0231ba88aead3?type=2');
            sleep(1);
            exit;
        }
        var_dump($login);exit;


        $auto=Autograph::find()->select('userid')->where(['doctorid'=>206260])->column();
        $child= ChildInfo::find()
            ->andFilterWhere(['in', '`child_info`.`userid`', array_unique($auto)])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
            ->all();
        foreach($child as $k=>$v){
            $rs=[];
            $rs[]=$v->name;
            $rs[]="\t".$v->idcard;
            $au=Autograph::findOne(['userid'=>$v->userid]);

            $rs[] = date('Y-m-d',$au->createtime);
            $userParent=UserParent::findOne(['userid'=>$v->userid]);
            if($userParent && $userParent->mother_phone){
                $rs[]="\t".$userParent->mother_phone;
            }else{
                $rs[]="\t".UserLogin::getPhone($v->userid);
            }
            echo implode(',',$rs);
            echo "\n";
        }
        exit;

        $doctorids=[110627];
        $doctors=UserDoctor::find()->where(['in','hospitalid',$doctorids])->column();
        $doctorParent=DoctorParent::find()->select('parentid')->where(['in','doctorid',$doctors])->column();

        $child=ChildInfo::find()->where(['in','source',$doctorids])->andWhere(['>','birthday',strtotime('-3 year')])->andWhere(['not in','userid',$doctorParent])->all();
        foreach($child as $k=>$v){
            $rs=[];
            $hospital=Hospital::findOne($v->source);
            $userParent=UserParent::findOne(['userid'=>$v->userid]);
            if($userParent && $userParent->mother_phone) {
                $rs[] = $userParent->mother_phone;
                $rs[] = $hospital->name;

            echo implode(',',$rs);
            echo "\n";
            }
        }
        exit;




        $time = ['8' => 1, '9' => 2, '10' => 3, '11' => 3, '13' => 4, '14' => 5, '15' => 6, '16' => 6];
        $list = FileHelper::findFiles('g');
        foreach ($list as $k => $v) {
            $appoint = [];
            $field = fopen($v, 'r');
            while (($line = fgets($field)) !== false) {
                $rs = explode(',', trim($line));
                if (intval($rs[0])) {
                    $birthday = strtotime($rs[3]);

                    $appointOrder = AppointOrder1::findOne(['birthday' => $birthday, 'name' => $rs[1]]);
                    if (!$appointOrder) {
                        $appointOrder = new AppointOrder1();
                        $appointOrder->birthday = $birthday;
                        $appointOrder->name = $rs[1];
                        $appointOrder->save();
                        var_dump($appointOrder->firstErrors);
                    }
                    $orderid = $appointOrder->id;


                    $app = new Appoint();
                    $da=explode(' ', $rs[2]);
                    $ti = explode(':', $da[1]);
                    $appoint['appoint_time'] = $time[$ti[0]];
                    $appoint['appoint_date'] = strtotime($da[0]);
                    $appoint['userid'] = 0;
                    $appoint['doctorid'] = 213390;
                    $appoint['type'] = 4;
                    $appoint['childid'] = $appointOrder->id;
                    $appoint['phone'] = 0;
                    $appoint['state'] = 2;
                    $appoint['login'] = 0;
                    $appoint['mode'] = 2;
                    $appoint['vaccine'] = 46;
                    $appoint['month'] = 0;
                    $appoint['orderid'] = $orderid;
                    $app->load(['Appoint' => $appoint]);
                    $app->save();
                    var_dump($app->firstErrors);

                }
            }
            fclose($field);
        }
        exit;



        $appoint=Appoint::find()->where(['doctorid'=>113890])->andWhere(['!=','state',3])->all();
        foreach($appoint as $k=>$v){
            if($v->type==4 ||$v->type==7){
                if($v->childid){
                    $name= \common\models\AppointAdult::findOne(['id' => $v->childid])->name;
                }else {
                    $name= \common\models\AppointAdult::findOne(['userid' => $v->userid])->name;
                }

            }elseif($v->type==5 || $v->type==6){
                $name= \common\models\Pregnancy::findOne(['id' => $v->childid])->field1;
            }else{
                $child= \common\models\ChildInfo::findOne(['id' => $v->childid]);
                if($child){
                    $name=$child->name;
                }
            }
            $row=[];
            $row[]=$name;
            $row[]=$v->phone;
            $row[]=\common\models\Appoint::$typeText[$v->type];

            $row[]=date('Y-m-d', $v->appoint_date);
            $row[]=\common\models\Appoint::$timeText[$v->appoint_time];
            if($v->vaccine==-2){
                $row[]="两癌筛查";
            }elseif($v->vaccine){
                $row[]=\common\models\Vaccine::findOne($v->vaccine)->name;
            }else{
                $row[]="";
            }
            echo implode(',',$row);
            echo "\n";
        }


        ini_set('memory_limit', '6000M');
        $totle=282750;
        $limit=ceil($totle/100);
        $snum=$num*$limit;

        $login=UserLogin::find()->select('openid')->where(['!=','openid',''])->groupBy('openid')->offset($snum)->limit($limit)->column();
        foreach($login as $k=>$v){
            $data = [
                'first' => ['value' => '秋冬季节是呼吸道传染病的流行季节，我们如何做好个人防护呢？在践行健康文明的生活方式里，在防控新冠肺炎流行的同时怎么能同时抵御流感等其他呼吸道传染病的威胁呢？不要着急，本期健康直播有幸邀请到孙瑛博士为您详细讲解。'],
                'keyword1' => ARRAY('value' => '秋冬季流行疾病预防及疫情防控，第十五期健康直播即将开始'),
                'keyword2' => ARRAY('value' => '2020年10月04日上午9点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data,$v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v1/course/alive/l_5fa124f0e4b01f764d887635?type=2');
            sleep(1);
        }
        var_dump($login);exit;

        //$hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['like','appoint',4]);

        $doctors = $query->orderBy('appoint desc')->all();


        foreach ($doctors as $k => $v) {
            $name= Hospital::findOne($v->hospitalid)->name;
            echo $name."\n";

        }
        exit;

        $appoint=Appoint::find()->select('phone')->where(['doctorid'=>160226,'state'=>1,'type'=>4])->andWhere(['in','vaccine',[56,55,54,0]])->column();
        foreach($appoint as $k=>$v){
            $rs=[];

            $rs[]=$v;
            $rs[]="西罗园社区卫生服务中心";
            $rs[]="四价宫颈癌疫苗";
            $rs[]="010-87289908";

            echo implode(',',$rs);
            echo "\n";
        }
        exit;
        $s_time='20201001';
        $e_time='20201026';


        $userDoctors = UserDoctor::find()->all();
        foreach ($userDoctors as $k => $v) {
            $rs = [];
            $rs[]=$v->county;

            $doctorParents2 = DoctorParent::find()->where(['doctorid' => $v->userid])
                ->select('parentid')
                ->column();


            $userLogin = UserLogin::find()->select('openid')->where(['in', 'userid', $doctorParents2])->andWhere(['!=', 'openid', ''])->groupBy('userid')->column();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=','createtime',strtotime($s_time)])->andWhere(['aid' => 1369])->groupBy('openid')->count();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=','createtime',strtotime($s_time)])->andWhere(['aid' => 1369])->groupBy('openid')->andWhere(['level' => 1])->count();
            $rs[] = "";

            //$userLogin=UserLogin::find()->select('openid')->where(['in','userid',$childs])->andWhere(['!=','openid',''])->groupBy('userid')->column();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=','createtime',strtotime($s_time)])->andWhere(['aid' => 1370])->groupBy('openid')->count();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=','createtime',strtotime($s_time)])->andWhere(['aid' => 1370])->groupBy('openid')->andWhere(['level' => 1])->count();
            $rs[] = "";
            echo $v->name . "," . implode(',', $rs);
            echo "\n";
        }

        exit;


        $i = '00:00';
        $time = date('H:i');
        $j = 1;
        while ($i != $time) {
            $time = date('H:i', strtotime("2020-09-18 +$j minute"));
            $j++;
            echo $time . "--";
            echo Appoint::getTimeType(2, $time);
            echo "\n";
        }
        exit;

        $time = ['08' => 1, '09' => 2, '10' => 3, '13' => 4, '14' => 5, '15' => 6, '16' => 6];
        $list = FileHelper::findFiles('g');
        foreach ($list as $k => $v) {
            $appoint = [];
            $field = fopen($v, 'r');
            while (($line = fgets($field)) !== false) {
                $rs = explode(',', trim($line));
                if (intval($rs[0])) {
                    $birthday = strtotime($rs[3]);
                    $appointOrder = AppointOrder1::findOne(['birthday' => $birthday, 'name' => $rs[1]]);
                    if (!$appointOrder) {
                        $appointOrder = new AppointOrder1();
                        $appointOrder->birthday = $birthday;
                        $appointOrder->name = $rs[1];
                        $appointOrder->save();
                        var_dump($appointOrder->firstErrors);
                    }
                    $orderid = $appointOrder->id;


                    $app = new Appoint();
                    $ti = explode(':', $rs[2]);
                    $appoint['appoint_time'] = $time[$ti[0]];
                    preg_match("/([0-9]{4})-([0-1]?[0-9]{1})-([0-3]?[0-9]{1})/", $v, $m);
                    $appoint['appoint_date'] = strtotime($m[0]);
                    $appoint['userid'] = 0;
                    $appoint['doctorid'] = 216593;
                    $appoint['type'] = 2;
                    $appoint['childid'] = 0;
                    $appoint['phone'] = 0;
                    $appoint['state'] = 2;
                    $appoint['login'] = 0;
                    $appoint['mode'] = 2;
                    $appoint['vaccine'] = 0;
                    $appoint['month'] = 0;
                    $appoint['orderid'] = $orderid;
                    $app->load(['Appoint' => $appoint]);
                    $app->save();
                    var_dump($app->firstErrors);

                }
            }
            fclose($field);
        }
        exit;


        for ($i = 1; $i < 200; $i++) {
            $curl = new HttpRequest('https://admin.xiaoe-tech.com/get/comment_admin_page?type=2&page=' . $i, true, 10);
            $curl->setHeader('Cookie', 'XIAOEID=ada9443a1046c1e9c6290d70dd6e9d80; cookie_channel=2-1568; cookie_is_signed=1; channel=16-6821; Hm_lvt_32573db0e6d7780af79f38632658ed95=1593418195,1593570326,1593573265,1594634419; dataUpJssdkCookie={"wxver":"","net":"","sid":""}; Hm_lvt_081e3681cee6a2749a63db50a17625e2=1595818576,1595913780,1596007326,1596092383; cookie_session_id=Rz4QVV3vyLL1ndEFFlHihygfcZ2s4N5V; b_user_token=token_5f2779139bd3fn84fvGcrIT5alVtTRQ1W; shop_type=TRQ1W; sensorsdata2015jssdkcross=%7B%22distinct_id%22%3A%22b_u_5e74684507bd0_LVMTUeNW%22%2C%22%24device_id%22%3A%22170f6b59dc0f3-08a5f8f462d0fa-396a7400-1024000-170f6b59dc149b%22%2C%22props%22%3A%7B%22%24latest_referrer%22%3A%22%22%2C%22%24latest_traffic_source_type%22%3A%22%E7%9B%B4%E6%8E%A5%E6%B5%81%E9%87%8F%22%2C%22%24latest_search_keyword%22%3A%22%E6%9C%AA%E5%8F%96%E5%88%B0%E5%80%BC_%E7%9B%B4%E6%8E%A5%E6%89%93%E5%BC%80%22%2C%22page_submodule%22%3A%22%E8%B4%A6%E5%8F%B7%E4%B8%BB%E9%A1%B5%22%2C%22page_name%22%3A%22%E8%B4%A6%E5%8F%B7%E4%B8%BB%E9%A1%B5_old%22%2C%22page_module%22%3A%22%E7%AE%A1%E7%90%86%E5%8F%B0%22%2C%22page_button%22%3A%22%22%7D%2C%22first_id%22%3A%22170f6b59dc0f3-08a5f8f462d0fa-396a7400-1024000-170f6b59dc149b%22%7D; appsc=appsx0v9q8I8331; with_app_id=appsx0v9q8I8331; Hm_lpvt_081e3681cee6a2749a63db50a17625e2=1596426127; laravel_session=eyJpdiI6InZGWG5paGxQRWpiUmhCUmxVZHp6V1E9PSIsInZhbHVlIjoiTVwvcmdyUktVbzN6WnpESnNCQzZRbVV0czR1aWpJWXV2MURQMzRxdmE1QlNLZ3ZKalNsS3JvMUZQT0xWRUZhN2c4TFZjYitpeEl2dHZqWGVETWd6Rm1RPT0iLCJtYWMiOiJkYTU4MWVlNzk3ZDcyM2FjODc3MDQ4ZTFmY2M3Njg4ZjBhYTZjYmYwMzQ2ODZlMzZmNzFiNmRlYzYwNWNjYzhkIn0%3D');
            $html = $curl->get();
            $data = json_decode($html, true);
            $list = $data['data']['data'];
            if ($list) {
                foreach ($list as $k => $v) {
                    $text = iconv("UTF-8", "GBK", $v['content']);
                    file_put_contents($v['record_title'] . '.csv', $text . "\n", FILE_APPEND);
                }
            } else {
                continue;
            }
        }

        exit;
        ini_set('memory_limit', '6000M');

        $doctors = Doctors::find()->all();
        foreach ($doctors as $k => $v) {
            $docHospital = DoctorHospital::findOne(['doctorid' => $v->userid, 'hospitalid' => $v->hospitalid]);
            $docHospital = $docHospital ? $docHospital : new DoctorHospital();
            $docHospital->doctorid = $v->userid;
            $docHospital->hospitalid = $v->hospitalid;
            $docHospital->save();
        }
        exit;

        $f = fopen('appoint_id.log', 'r');
        while (($line = fgets($f)) !== false) {
            preg_match('/id=(\d*)/', $line, $m);
            if (intval($m[1])) {
                $id[] = $m[1];
            }
        }
        $appoint = Appoint::find()->select('count(*) as a')->having(['>', 'a', 1000])->indexBy('doctorid')->where(['in', 'id', $id])->groupBy('doctorid')->column();
        $userDoctor = UserDoctor::find()->select('name')->where(['in', 'userid', array_keys($appoint)])->column();
        var_dump($userDoctor);
        exit;

        $f = fopen('123.csv', 'r');
        while (($line = fgetcsv($f)) !== false) {
            $rs = [];
            $phone = trim($line[19]);
            $name = trim($line[18]);
            $rs[] = '';
            $rs[] = $name;
            $preg = Pregnancy::find()->where(['field1' => $name, 'field6' => $phone])->andWhere(['>', 'field11', strtotime('-11 month')])->one();
            if ($preg) {

            } else {
                $preg = Pregnancy::find()->where(['field1' => $name, 'doctorid' => 110599])->andWhere(['>', 'field11', strtotime('-11 month')])->one();
            }
            if ($preg) {
                $rs[] = $this->getAgeByBirth($preg->field2, 2);
                $rs[] = $preg->field4;
                $rs[] = $phone;
                $rs[] = $preg->field5;
                $rs[] = date('Y-m-d', $preg->field11);
                $rs[] = $preg->field81;

            } else {
                $rs[] = '';
                $rs[] = '';
                $rs[] = '';
                $rs[] = '';
                $rs[] = '';
                $rs[] = '';

            }
            $rs[] = trim($line[20]);
            $rs[] = trim($line[21]);
            $rs[] = trim($line[22]);
            $rs[] = trim($line[23]);
            $rs[] = trim($line[24]);
            $rs[] = trim($line[25]);
            echo implode("\t,", $rs);
            echo "\n";
        }
        exit;

        $auto = Autograph::findAll(['doctorid' => 206260]);

        foreach ($auto as $k => $v) {
            $rs = [];
            $preg = Pregnancy::find()->where(['familyid' => $v->userid])->andWhere(['>', 'field11', strtotime('-11 month')])->one();
            if ($preg) {
                $openid = UserLogin::getOpenid($v->userid);
                echo $openid . "\n";
            }
        }

        exit;


        $auto = Autograph::find()->andWhere(['starttime' => 0])->all();
        foreach ($auto as $k => $v) {
            $v->starttime = date('Ymd', $v->createtime);
            //$v->endtime=date('Ymd',strtotime('+1 year',strtotime($v->starttime)));
            $v->save();
        }
        exit;


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


        $satime = strtotime('-1 day', strtotime(date('Y-m-d')));
        $doctors = UserDoctor::find()->where(['county' => 1106])->all();
        foreach ($doctors as $k => $v) {
            echo $v->name;
            for ($stime = $satime; $stime < time(); $stime = strtotime('+1 day', $stime)) {
                echo date('Ymd', $stime);
                $etime = strtotime('+1 day', $stime);
                $doctorParent = DoctorParent::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['>=', 'createtime', $stime])
                    ->andWhere(['<', 'createtime', $etime])
                    ->count();
                $child_info1 = ChildInfo::find()
                    ->leftJoin('doctor_parent', 'doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid' => $v->userid])
                    ->leftJoin('autograph', 'autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'autograph.createtime', $stime])
                    ->andWhere(['<', 'autograph.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year', $stime)])
                    ->andWhere('autograph.createtime>=child_info.createtime')
                    ->count();
                $child_info2 = ChildInfo::find()
                    ->leftJoin('doctor_parent', 'doctor_parent.parentid=child_info.userid')
                    ->andWhere(['doctor_parent.doctorid' => $v->userid])
                    ->leftJoin('autograph', 'autograph.userid=child_info.userid')
                    ->andWhere(['>=', 'child_info.createtime', $stime])
                    ->andWhere(['<', 'child_info.createtime', $etime])
                    ->andWhere(['>=', 'child_info.birthday', strtotime('-6 year', $stime)])
                    ->andWhere('autograph.createtime<child_info.createtime')
                    ->count();

                $pregLCount = Pregnancy::find()
                    ->andWhere(['>', 'pregnancy.field16', strtotime('-43 week', $stime)])
                    //->leftJoin('autograph', '`autograph`.`userid` = `pregnancy`.`familyid`')
                    ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
                    ->andWhere(['>=', 'doctor_parent.createtime', $stime])
                    ->andWhere(['<', 'doctor_parent.createtime', $etime])
                    ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])->count();


                $appoint = Appoint::find()->where(['doctorid' => $v->userid])
                    ->andWhere(['appoint_date' => $stime])
                    ->andWhere(['!=', 'state', 3])
                    ->count();
                $rs = [];
                $rs['sign1'] = $doctorParent;
                $rs['sign2'] = $child_info1 + $child_info2;
                $rs['sign3'] = $pregLCount;

                $rs['appoint_num'] = $appoint;
                $rs['doctorid'] = $v->userid;
                $rs['date'] = $stime;
                var_dump($rs);
                $hospitalFrom = HospitalForm::find()->where(['doctorid' => $v->userid])->andWhere(['date' => $stime])->one();
                $hospitalFrom = $hospitalFrom ? $hospitalFrom : new HospitalForm();
                $hospitalFrom->load(['HospitalForm' => $rs]);
                $hospitalFrom->save();
                var_dump($hospitalFrom->firstErrors);
            }
        }
        exit;


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

                    echo $k . "\n";

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

}