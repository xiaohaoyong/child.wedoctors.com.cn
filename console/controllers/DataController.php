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
use common\helpers\IdcardValidator;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Access;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\AppointList;
use common\models\AppointOrder;
use common\models\AppointOrder1;
use common\models\Area;
use common\models\Article;
use common\models\ArticleComment;
use common\models\ArticleInfo;
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
use common\models\HealthRecords;
use common\models\HealthRecordsSchool;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointWeek;
use common\models\HospitalForm;
use common\models\Interview;
use common\models\Log;
use common\models\Notice;
use common\models\Points;
use common\models\Pregnancy;
use common\models\Test;
use common\models\Test1;
use common\models\TmpLog;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\Vaccine;
use common\models\MoveChild;
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
use Cache\Adapter\Redis\RedisCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use common\models\DoctorTeam;

class DataController extends \yii\console\Controller
{
    function getIDCardInfo($IDCard,$format=1){
        $result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误
        $result['flag']='';//0标示成年，1标示未成年
        $result['tdate']='';//生日，格式如：2012-11-15
        if(false){
         $result['error']=1;
         return $result;
        }else{
         if(strlen($IDCard)==18)
         {
          $tyear=intval(substr($IDCard,6,4));
          $tmonth=intval(substr($IDCard,10,2));
          $tday=intval(substr($IDCard,12,2));
         }
         elseif(strlen($IDCard)==15)
         {
          $tyear=intval("19".substr($IDCard,6,2));
          $tmonth=intval(substr($IDCard,8,2));
          $tday=intval(substr($IDCard,10,2));
         }
           
         if($tyear>date("Y")||$tyear<(date("Y")-100))
         {
           $flag=0;
          }
          elseif($tmonth<0||$tmonth>12)
          {
           $flag=0;
          }
          elseif($tday<0||$tday>31)
          {
           $flag=0;
          }else
          {
           if($format)
           {
            $tdate=$tyear."-".$tmonth."-".$tday;
           }
           else
           {
            $tdate=$tmonth."-".$tday;
           }
             
           if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60)
           {
            $flag=0;
           }
           else
           {
            $flag=1;
           }
          } 
        }
        $result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误
        $result['isAdult']=$flag;//0标示成年，1标示未成年
        $result['birthday']=$tdate;//生日日期
        return $tdate;
       }
    function getfiles($path, $allowFiles = '', $depth = 1, $substart = 0, &$files = array()){
        $depth--;
        $path = realpath($path) . '/';
        $substart = $substart ? $substart : strlen($path);

        if (!is_dir($path)){
            return false;
        }

        if($handle = opendir($path)){
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    $path2 = $path . $file;
                    if (is_dir($path2) && $depth > 0){
                        getfiles($path2, $allowFiles, $depth, $substart, $files);
                    } elseif (empty($allowFiles) || preg_match($allowFiles, $file)) {
                        $files[] = substr($path2, $substart);
                    }
                }
            }
        }
        sort($files);
        return $files;
    }
    public function actionTesta($doctorid=0)
    {
        // $article = ArticleUser::findAll(['touserid' => $v['userid']]);

        
        // $userDoctor=UserDoctor::findAll(['is_team'=>1]);
        // foreach($userDoctor as $k=>$v){
        //     echo $v->name."\n";
        //     $childTeam=DoctorTeam::find()->select('id')->where(['doctorid'=>$v->userid])->andWhere(['in','type',[0,1]])->column();
        //     if($childTeam){
        //         $teamCount = ChildInfo::find()
        //             ->select('count(*)')
        //             ->indexBy('child_info.teamid')
        //             ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
        //             ->andWhere(['doctor_parent.doctorid'=>$v->userid])
        //             ->andWhere(['in', 'child_info.teamid', $childTeam])
        //             ->groupBy('child_info.teamid')
        //             ->column();
        //         if(!$teamCount){
        //             $teamCount=array_fill_keys($childTeam,0);
        //         }

        //         $childs = ChildInfo::find()
        //             ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
        //             ->andWhere(['doctor_parent.doctorid'=>$v->userid])
        //             ->andWhere(['child_info.teamid'=>0])
        //             ->all();
        //         foreach($childs as $dv){
        //             asort($teamCount);
        //             $key = array_key_first($teamCount);
        //             $dv->teamid = $key;
        //             $dv->save();
        //             $teamCount[$key]++;
        //             printf("progress: %s", implode(',',$teamCount));
        //             echo "\n";
        //         }
        //     }
        //     $pTeam=DoctorTeam::find()->select('id')->where(['doctorid'=>$v->userid])->andWhere(['in','type',[0,2]])->column();
        //     var_dump($pTeam);
        //     if($pTeam){
        //             $teamCount = Pregnancy::find()
        //             ->select('count(*)')
        //             ->indexBy('pregnancy.teamid')
        //             ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
        //             ->andWhere(['doctor_parent.doctorid'=>$v->userid])
        //             ->andWhere(['in', 'pregnancy.teamid', $pTeam])
        //             ->groupBy('pregnancy.teamid')
        //             ->column();
        //             if(!$teamCount ){
        //                 $teamCount=array_fill_keys($pTeam,0);
        //             }
        //         $pregnancys = Pregnancy::find()
        //             ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
        //             ->andWhere(['doctor_parent.doctorid'=>$v->userid])
        //             ->andWhere(['pregnancy.teamid'=>0])
        //             ->all();
        //         foreach($pregnancys as $dk=>$dv){
        //             asort($teamCount);
        //             $key = array_key_first($teamCount);
        //             $dv->teamid = $key;
        //             $dv->save();
        //             $teamCount[$key]++;
        //             printf("progress: %s", implode(',',$teamCount));
        //             echo "\n";
        //         }
        //     }


            // $doctorTeam=DoctorTeam::findOne(['doctorid'=>$v->userid]);
            // if($doctorTeam) {
            //     $doctor = $v;
            //     if ($doctor && $doctor->is_team) {
            //         $doctorParent = DoctorParent::find()->select('count(*) as a,teamid')->where(['doctorid' => $this->doctorid])->andWhere(['>', 'teamid', 0])->groupBy('teamid')->orderBy('a asc')->one();
            //         if (!$doctorParent) {
            //             $this->teamid = $doctorTeam->id;
            //         } else {
            //             $this->teamid = $doctorParent->teamid;
            //         }
            //     }
            // }
            
        

        $file = fopen('3321.csv', 'r');
        $i=0;
        while (($line = fgets($file)) !== false) {
            $rs = explode(',',trim($line));
            $child=ChildInfo::find()
            //    ->leftJoin('user_login', '`user_login`.`userid` = `child_info`.`userid`')
            //     ->andWhere(['`user_login`.`phone`' => $rs[2]])
//                ->leftJoin('user_parent', '`user_parent`.`userid` = `pregnancy`.`familyid`')
//                ->andWhere(['user_parent.mother_id'=>$phone])
//                ->orWhere(['pregnancy.field4'=>$phone])
                    //->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                    //->andWhere(['user_parent.mother'=>$rs[6]])
                    //->andWhere(['doctor_parent.doctorid'=>175877])
                     ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                     ->andWhere(['doctor_parent.doctorid'=>190922])
                    ->andWhere(['child_info.name'=>$rs[0]])
                    //->andWhere(['child_info.birthday'=>strtotime($rs[4])])

                    ->one();
            //echo "\t".$phone.",";
            //var_dump($child);
           if($child){
                $phone= UserLogin::getPhone($child->userid);
                $rs[7]=$phone;
           }
           echo implode(',',$rs);
           echo "\n";
           
        }
        exit;

        $file = fopen('121212.csv', 'r');
        $i=0;
        while (($line = fgets($file)) !== false) {
            $rs=explode(',',trim($line));
            $child=Pregnancy::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
                ->andWhere(['doctor_parent.doctorid'=>175877])
                ->andWhere(['pregnancy.field1'=>$rs[0]])
                //->andWhere(['pregnancy.field11'=>strtotime($rs[12])])

                //->andWhere(['pregnancy.field4'=>''])
                //->andWhere(['>','pregnancy.field11',strtotime('-43 week')])
 
                //->andWhere(['pregnancy.field2'=>strtotime($rs[5])])
                ->one();
                if($child){
                    $child->field4 = $rs[1];
                    $child->save();
                    echo $rs[0];
                    echo "\n";
                }
                // if(!$child){
                //     $i++;
                // }
                // $rs[]=$child?'已签约':'未签约';
                // $rs[]="\n";
                // $l = implode(',',$rs);
                // //file_put_contents("333333.csv",$l,FILE_APPEND);
        }
        echo $i;
        exit;

        $IdV=new IdcardValidator();


        $return=$IdV->idCardVerify('110103198805060024');
        var_dump($return);exit;

        $totle = 507593;
        $limit = ceil($totle / 20);
        $snum = $num * $limit;

        $data = [
            'first' => ['value' => "HPV 疫苗作为目前可以有效预防宫颈癌的疫苗，一直以来被高度关注。该怎样选择，接种疫苗有什么注意事项等等，也是朋友们关心的话题。为此儿宝宝邀请了北京大学国际医院特需国际医疗部成人疫苗门诊，主治医师 刘芳勋和北京王府中西医结合医院 主任医师 医学博士 妇产科专家 董文辉来给大家进行讲解，宫颈癌的预防策略及HPV疫苗该怎么选。"],
            'keyword1' => ARRAY('value' => '第三十三期健康直播课HPV疫苗怎么选'),
            'keyword2' => ARRAY('value' => '2022年12月11日 下午15点'),
            'remark' => ARRAY('value' => ""),
        ];
        $url='https://kfl.h5.xeknow.com/sl/2u3uAK';
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'ob5OPSvdOJ2DhK13eDdnI9PwABfDjDDssTIGHjYB_WQ', $url);
        exit;
        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->andWhere(['type'=>0])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {

            $rs = WechatSendTmp::send($data, $v, 'ob5OPSvdOJ2DhK13eDdnI9PwABfDjDDssTIGHjYB_WQ', $url);
            var_dump($v);
        }
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'ob5OPSvdOJ2DhK13eDdnI9PwABfDjDDssTIGHjYB_WQ', $url);
        exit;

        $rs['name'] = 'sdf';
        $rs['birthday'] = 'sdf';
        $rs['mother'] = 'sdf';
        $rs['hospitalid'] = 'sdf';
        $rs['idcard'] = 'sdf';
        $moveChild = new MoveChild();

        $ids=DoctorParent::find()->select('parentid')->where(['doctorid'=>353548])->column();
        $preg = Pregnancy::find()->where(['<','field11',strtotime('-37 week')])
            ->select('familyid')
            ->andWhere(['>','field11',strtotime('-48 week')])
            ->andWhere(['field49'=>0])
            ->andWhere(['familyid'=>$ids])
            ->column();
        $child = ChildInfo::find()->select('userid')->where(['>','birthday',strtotime('-3 month')])->andWhere(['userid'=>$ids])->column();
        $userids = array_unique($preg+$child);
        $data = [
            'first' => ['value' => "恭喜您有了或即将有一个健康的宝宝，为了更好的给宝宝提供优质的接种服务，本单位开展线上家长课堂"],
            'keyword1' => ARRAY('value' => '疫苗接种-新手妈妈早知道'),
            'keyword2' => ARRAY('value' => '2022年11月25日20:30-21:00'),
            'keyword3' => ARRAY('value' => '2022年11月25日20:30-21:00'),

            'remark' => ARRAY('value' => ""),
        ];
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=2398",
        ];
        foreach($userids as $k=>$v){

            $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->andWhere(['userid'=>$v])->one();
           
            $rs = WechatSendTmp::send($data, $login->openid, 'VXAAPM2bzk1zGHAOnj8cforjriNp3wsg4ZewGEUck_0', '', $miniprogram);
            var_dump($rs);
        }
exit;


//        $fiels=$this->getfiles('/Users/wangzhen/PhpstormProjects/child.wedoctors.com.cn/123', '#\.(xlsx)$#', 3);
//        foreach($fiels as $k=>$v){
//            $fname=$v;
//            $excelInfo['path'] = '/Users/wangzhen/PhpstormProjects/child.wedoctors.com.cn/123/'.$fname;
//            $inputFileType = PHPExcel_IOFactory::identify($excelInfo['path']);
//            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
//
//            $objPHPExcels = $objReader->load($excelInfo['path']);
//            $maxRow = $objPHPExcels->getSheet(0)->getHighestRow();#总行数
//            echo $maxRow;
//            echo "\n";
//
//        }
//        exit;
//        $file = fopen('idcard1.csv', 'r');
//        while (($line = fgets($file)) !== false) {
//            $rs = explode(',',trim($line));
//
//            $child_info = ChildInfo::findOne(['idcard'=>$rs[3]]);
//            if($child_info){
//                //echo $child_info->name;
//            }else{
//                $rs = explode(',',trim($line));
//                $birthday = substr($rs[3],6,8);
//                $count = ChildInfo::find()->where(['name'=>$rs[0],'birthday'=>strtotime($birthday)])->count();
//                if($count == 1) {
//                    $child_info = ChildInfo::findOne(['name' => $rs[0], 'birthday' => strtotime($birthday)]);
//                }elseif($count > 1){
//                    $rm = '同名同生日超过一个无法确定';
//                }else{
//                    $rm = '未查询到孩子';
//                }
//            }
//            echo trim($line);
//            if($child_info) {
//                echo ",已修改";
//                $userParent = DoctorParent::findOne(['parentid' => $child_info->userid]);
//                if ($userParent) {
//                    $userParent->doctorid = 353548;
//                    $userParent->save();
//                    $auto = Autograph::findOne(['userid' => $child_info->userid]);
//                    if ($auto) {
//                        $auto->doctorid = 353548;
//                        $auto->save();
//                    }
//                }
//            }else{
//                echo ",未修改,".$rm;
//            }
//
//            echo "\n";
//        }
//        exit;

//        $data = [
//            'first' => ['value' => '您预约的九价宫颈癌疫苗，已修改至2022年9月7日星期三'],
//            'keyword1' => ARRAY('value' => '新街口社区卫生服务中心'),
//            'keyword2' => ARRAY('value' => '010-66184175'),
//            'keyword3' => ARRAY('value' => '如有问题可联系儿宝宝小助手（erbbzs）或拨打社区医院电话'),
//            'remark' => ARRAY('value' => ""),
//        ];
//        $tmpid='3ui_xwyZXEw4DK4Of5FRavHDziSw3kiUyeo74-B0grk';
//        $rs = WechatSendTmp::send($data, 'o5ODa00hsX2jkXFNsttaBQnmY8V8', $tmpid);
//        var_dump($rs);
//        exit;

//        $count=Autograph::find()->count();
//        echo $count;
        $file = fopen('123.csv', 'r');
        $i=0;
        while (($line = fgets($file)) !== false) {
            $phone = trim($line);
            $rs = explode(',',$phone);
            $child=ChildInfo::find()
//                ->select('user_login.phone')
//                ->leftJoin('user_login', '`user_login`.`userid` = `child_info`.`userid`')
//                ->andWhere(['`user_login`.`phone`' => $phone])
//                ->leftJoin('user_parent', '`user_parent`.`userid` = `pregnancy`.`familyid`')
//                ->andWhere(['user_parent.mother_id'=>$phone])
//                ->orWhere(['pregnancy.field4'=>$phone])
                ->andWhere(['child_info.name'=>$rs[0]])
                ->andWhere(['child_info.birthday'=>strtotime($rs[2])])
                ->andWhere(['child_info.doctorid'=>110605])
                ->one();
            //echo "\t".$phone.",";
            if($child) {
                //echo $child->phone;
               //echo "\t".$phone;
                $userLogin = UserLogin::findOne(['userid'=>$child->userid]);
                $rs[3]=$userLogin->phone;
            }
            echo implode(',',$rs);
            echo "\n";
        }
        exit;
//        $count=Autograph::find()->count();
//        echo $count;
//        exit;
//        $file = fopen('216593.csv', 'r');
//        $i=0;
//        while (($line = fgets($file)) !== false) {
//            $rs=explode(',',trim($line));
//            if(!$phone=trim(str_replace('"','',$rs[7]))){
//                continue;
//            }
//            $child=Pregnancy::find()
//                ->leftJoin('user_login', '`user_login`.`userid` = `pregnancy`.`familyid`')
//                ->andFilterWhere(['`user_login`.`phone`' => $phone])
//                ->andWhere(['pregnancy.field1'=>$rs[2]])
//                ->andWhere(['pregnancy.field4'=>''])
//                //->andWhere(['>','pregnancy.field11',strtotime('-43 week')])
//
//                //->andWhere(['pregnancy.field2'=>strtotime($rs[5])])
//                ->one();
//            var_dump($child);
//
//            if($rs[4] && $child){
//                echo $rs[2];echo "\n";
//                $child->field4=trim($rs[4]);
//                //$child->field2=strtotime($rs[5]);
//                $child->save();
//                $i++;
//                $userParent=UserParent::findOne(['userid'=>$child->familyid]);
//                if($userParent){
//                    $userParent->mother_id=$rs[4];
//                    $userParent->save();
//                }
//            }else{
//                echo "===\n";
//            }
//        }
//        exit;
        $file = fopen('gw.csv', 'r');
        $i=0;
        while (($line = fgets($file)) !== false) {
            $rs=explode(',',trim($line));
            $child=ChildInfo::find()
                //->leftJoin('user_login', '`user_login`.`userid` = `child_info`.`userid`')
                //->andWhere(['`user_login`.`phone`' => $phone])
                ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                ->andWhere(['user_parent.mother'=>$rs[2]])
                ->andWhere(['child_info.name'=>$rs[0]])
                ->andWhere(['child_info.birthday'=>strtotime($rs[1])])
                ->one();
            if($rs[4] && $child){
                echo $rs[2];echo "\n";
                $child->field27=trim($rs[4]);
                $child->save();
                $i++;
//                $userParent=UserParent::findOne(['userid'=>$child->userid]);
//                if($userParent){
//                    $userParent->fieldu46=trim($rs[3]);
//                    $userParent->fieldp47=trim($rs[3]);
//                    $userParent->save();
//                }
            }else{
                echo "===\n";
            }
        }
        echo $i;
        exit;
        $IdV=new IdcardValidator();
        $file = fopen('1.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $line=iconv("GBK", "UTF-8//IGNORE", $line);
            $rs=explode(',',trim($line));
            $rs[1]=preg_replace('/^0*/', '', $rs[1]);
            if($rs[6]){
                $rs1[$rs[1]][$rs[5]]=trim($rs[6]);
            }
        }
        $file = fopen('2.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $line=iconv("GBK", "UTF-8//IGNORE", $line);
            $rs=explode(',',trim($line));
            $rs[6]=str_replace('*','',$rs[6]);

            if($rs1[$rs[1]][$rs[5]] && !$IdV->idCardVerify($rs[6])){
               $rs[6]=$rs1[$rs[1]][$rs[5]]."\t";
            }
            echo implode(',',$rs);
            echo "\n";
        }
        exit;

        $array=[];
        $file = fopen('2.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $row = explode(',', trim($line));
            $idcard=str_replace('*','',$child->field27);
            if($array[$row[2]]){
                if($array[$row[2]][$row[6]] && $array[$row[2]][$row[6]]==$row[3]){
                    continue;
                }
            }
            $array[$row[2]][$row[6]] = $row[3];
            echo $line;
        }exit;


        $stime=1653235200;
        $etime=1653840000;
        $userDoctor = UserDoctor::find()->select('county')->groupBy('county')->all();
        foreach ($userDoctor as $k=>$v){
            $rs=[];
            $doctorids = UserDoctor::find()->select('userid')->where(['county'=>$v->county])->column();
            //推送总人数（小于74天的）
            $tmpLog=TmpLog::find()
                ->select('openid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['fid'=>1985])
                ->column();
            $rs[]=Area::$all[$v->county];
            $rs[]=count($tmpLog);
            //打开人数(小于74天的)

            $userids=UserLogin::find()->select('userid')->where(['in','openid',$tmpLog])->column();
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['in','userid',$userids])
                ->andWhere(['cid'=>1985])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //停留10秒以上的人数

            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['in','userid',$userids])
                ->andWhere(['>','long',9])
                ->andWhere(['cid'=>1985])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //预约弹窗总人数（小于74天）
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['type'=>1])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //首页视频打开数
            $acc=Access::find()
                ->select('userid')
                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['cid'=>1371])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //首页视频停留到播放20秒的人数（目前无五联内容）
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['cid'=>1371])
                ->andWhere(['>','long',20])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //五联视频链接的点开人数
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['type'=>4])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //视频停留30秒以上的人数
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['cid'=>1983])
                ->andWhere(['>','long',30])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            //视频停留15秒以上的人数
            $acc=Access::find()
                ->select('userid')

                ->where(['>','createtime',$stime])
                ->andWhere(['<','createtime',$etime])
                ->andWhere(['in','doctorid',$doctorids])
                ->andWhere(['cid'=>1983])
                ->andWhere(['>','long',15])
                ->column();
            $acc=array_unique($acc);
            $rs[]=count($acc);
            echo implode(',',$rs);
            echo "\n";
        }

        exit;
        $access=Access::find()->where(['doctorid'=>0])->all();
        foreach($access as $k=>$v){
            $doctorParent=DoctorParent::findOne(['parentid'=>$v->userid]);
            if($doctorParent) {
                $v->doctorid = $doctorParent->doctorid;
                $v->save();
                var_dump($v->firstErrors);
            }else{
                var_dump($v);
            }
            echo "\n";
        }
        $tmp=TmpLog::find()->where(['doctorid'=>0])->andWhere(['fid'=>1985])->all();
        foreach($tmp as $k=>$v){
            $userLogin=UserLogin::findOne(['openid'=>$v->openid]);
            $doctorParent=DoctorParent::findOne(['parentid'=>$userLogin->userid]);
            $v->doctorid=$doctorParent->doctorid;
            $v->save();
            echo 123;
            echo "\n";
        }
        exit;

        $data = [
            'first' => ['value' => "在“女神节”这个宠爱与被宠爱的节日，儿宝宝邀请了张兰萍主任为我们进行直播答疑，解读宫颈癌疫苗你想知道的那些事，同时在直播间更有HPV二价疫苗首针免费抽奖，九价疫苗预约指引，姐妹们一起冲鸭。"],
            'keyword1' => ARRAY('value' => '迎女神节福利：专家直播答疑，抽奖免费送HPV二价首针，九价疫苗预约指引'),
            'keyword2' => ARRAY('value' => '2022年3月8日 晚19点'),
            'keyword3' => ARRAY('value' => '2022年3月8日 晚19点'),

            'remark' => ARRAY('value' => ""),
        ];
        $url='https://kfl.h5.xeknow.com/sl/3qJCnD';
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'VXAAPM2bzk1zGHAOnj8cforjriNp3wsg4ZewGEUck_0', $url,[],123);

        var_dump($rs);
        exit;
        $file = fopen('fengtai.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $row = explode(',', trim($line));
            $a=$row[3];
            $d=date('Y-m',strtotime($a));
//            if(!file_exists('data/'.$row[0])){
//                mkdir('data/'.$row[0]);
//            }
            if($row[0]) {
                $row[2]= $row[2]."\t";
                $row[]="\n";
                file_put_contents('data/' . $row[0] . ".csv", implode(',',$row), FILE_APPEND);
            }
        }exit;
        $doctorid=UserDoctor::find()->select('userid')->where(['county'=>1106])->column();
        $doctorParent=DoctorParent::find()->where(['in','doctorid',$doctorid])->andWhere(['<','createtime',strtotime('2022-05-01')])->all();
        foreach($doctorParent as $k=>$v){
            $child=ChildInfo::find()->where(['userid'=>$v->parentid])->all();
            foreach($child as $ck=>$cv){
                $userParent=UserParent::findOne(['userid'=>$cv->userid]);
                if($userParent){
                    $phone=$userParent->getPhone();
                }else{
                    continue;
                }
                $userDoctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
                $hospital=Hospital::findOne(['id'=>$userDoctor->hospitalid]);
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $cv->birthday));
                if ($DiffDate[0]) {
                    $age = $DiffDate[0] . "岁";
                } elseif ($DiffDate[1]) {
                    $age = $DiffDate[1] . "月";
                } else {
                    $age = $DiffDate[2] . "天";
                }
                $rs=[];
                $rs[]=$hospital->name;
                $rs[]=$cv->name;
                $idcard=$cv->field27?$cv->field27:$cv->idcard;
                $idcard=$idcard=='*****'?'':$idcard;
                $rs[]=$idcard==''?$cv->field6:$idcard;
                $rs[]=date('Y-m-d',$v->createtime);
                $rs[]=$age;
                $rs[]=$phone;
                $rs[]="儿童";
                echo implode(',',$rs);
                echo "\n";
            }
            $preg=Pregnancy::findAll(['familyid'=>$v->parentid]);
            foreach($preg as $pk=>$pv){
                $userParent=UserParent::findOne(['userid'=>$pv->familyid]);
                if($userParent){
                    $phone=$userParent->getPhone();
                }
                $userDoctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
                $hospital=Hospital::findOne(['id'=>$userDoctor->hospitalid]);
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $pv->field2));
                if ($DiffDate[0]) {
                    $age = $DiffDate[0] . "岁";
                } elseif ($DiffDate[1]) {
                    $age = $DiffDate[1] . "月";
                } else {
                    $age = $DiffDate[2] . "天";
                }
                $rs=[];
                $rs[]=$hospital->name;
                $rs[]=$pv->field1;
                $rs[]=$pv->field4;
                $rs[]=date('Y-m-d',$v->createtime);
                $rs[]=$age;
                $rs[]=$phone;
                $rs[]="孕妇";
                echo implode(',',$rs);
                echo "\n";
            }
        }
        exit;
        $childs = ChildInfo::findAll(['doctorid' => 110588]);
        if ($childs) {
            foreach($childs as $k=>$child) {
                $auto = Autograph::findOne(['userid' => $child->userid]);
                if ($auto) {
                    $auto->doctorid = 353548;
                    $auto->save();
                }
                $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
                if ($doctorParent) {
                    //$doctorParent->createtime = time();
                    $doctorParent->doctorid = 353548;
                    $doctorParent->save();
                }
                $child->doctorid = 110641;
                if ($child->admin) {
                    $child->admin = 110641;
                }
                if($child->source){
                    $child->source = 110641;

                }
                $child->save();
            }
        }
        exit;
        $data = [
            'first' => ['value' => "在“女神节”这个宠爱与被宠爱的节日，儿宝宝邀请了张兰萍主任为我们进行直播答疑，解读宫颈癌疫苗你想知道的那些事，同时在直播间更有HPV二价疫苗首针免费抽奖，九价疫苗预约指引，姐妹们一起冲鸭。"],
            'keyword1' => ARRAY('value' => '迎女神节福利：专家直播答疑，抽奖免费送HPV二价首针，九价疫苗预约指引'),
            'keyword2' => ARRAY('value' => '2022年3月8日 晚19点'),
            'keyword3' => ARRAY('value' => '2022年3月8日 晚19点'),

            'remark' => ARRAY('value' => ""),
        ];
        $url='https://kfl.h5.xeknow.com/sl/3qJCnD';
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'VXAAPM2bzk1zGHAOnj8cforjriNp3wsg4ZewGEUck_0', $url);

        var_dump($rs);
        exit;
        //签约儿童总数
        $todayNumTotal=ChildInfo::find()
            ->select('name')
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['`child_info`.`source`' => 110555])
            ->andFilterWhere(['`child_info`.admin'=>110555])
            ->all();
        foreach($todayNumTotal as $k=>$v){
            echo implode(',',$v->toArray());
            echo "\n";
        }
        exit;


        //管辖儿童数（0-3）
        $data['childNum']=ChildInfo::find()
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['`child_info`.`source`' => 110555])
            ->andFilterWhere(['`child_info`.admin'=>110555])
            ->createCommand()->getRawSql();
        var_dump($data);exit;
        $child=ChildInfo::find()->where(['source'=>110605])->andWhere(['doctorid'=>110605])->andWhere(['>','birthday',1555643600])->andWhere(['in','userid',DoctorParent::find()->select('parentid')->where(['doctorid'=>216593])->column()])->all();
        foreach($child as $k=>$v){
            echo implode(',',$v->toArray());
            echo "\n";
        }
        exit;
        $child=ChildInfo::find()->select('name,birthday,count(*) as a')->where(['doctorid'=>110605])->groupBy('name,birthday')->having('a>1')->all();
        foreach($child as $v){
            $childInfo=ChildInfo::find()->where(['name'=>$v['name']])->andWhere(['birthday'=>$v['birthday']])->andWhere(['source'=>110605])->one();
            if($childInfo) {
                $childInfotmp = ChildInfo::find()->where(['name' => $v['name']])->andWhere(['birthday' => $v['birthday']])->andWhere(['source' => 0])->one();
                $data = $childInfo->toArray();
                unset($data['id']);
                unset($data['userid']);
                $data1 = array_filter($data, function ($e) {
                    if ($e == '') {
                        return false;
                    }
                    return true;
                });
                if ($data1 && $childInfotmp) {
                    $childInfotmp->load(['ChildInfo' => $data1]);
                    $childInfotmp->save();
                    $childInfo->delete();
                }
            }
        }
        echo count($child);
        exit;




        $field4=substr('***************6445',-4);
        $preg=\common\models\Pregnancy::find()->where(['field1'=>'马玉晶'])->filterWhere(['SUBSTRING(field4, -4)'=>$field4])->all();
        var_dump($preg);exit;

        $file = fopen('1234.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $row = explode(',', trim($line));


            $name = $row[0];
            $birthday = $row[1];
            $phone1=intval(trim(str_replace('"','',$row[2])));
            $phone3=intval(trim(str_replace('"','',$row[3])));

            $query=UserLogin::find();

            if($phone1 && $phone3)
            {
                $query->where(['phone'=>$phone1])->orWhere(['phone'=>$phone3]);
            }elseif($phone1 && !$phone3){
                $query->where(['phone'=>$phone1]);
            }elseif(!$phone1 && $phone3){
                $query->where(['phone'=>$phone3]);
            }else{
                continue;
            }
            $login=$query->one();


            if($login) {
                $child = ChildInfo::findOne(['name' => $name, 'birthday' => strtotime($birthday),'userid'=>$login->userid]);
                if ($child) {
                    $auto = Autograph::findOne(['userid' => $child->userid]);
                    if ($auto) {
                        $auto->doctorid = 206260;
                        $auto->save();
                    }
                    $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
                    if ($doctorParent) {
                        $doctorParent->createtime = time();
                        $doctorParent->doctorid = 206260;
                        $doctorParent->save();
                    }
                    $child->doctorid = 110599;
                    if ($child->admin) {
                        $child->admin = 110599;
                    }
                    $child->save();
                    echo trim($line)."true";
                } else {
                    echo trim($line)."false child";
                }
            }else{
                echo trim($line)."false login";
            }
            echo "\n";
        }
        exit;

        $totle = 507593;
        $limit = ceil($totle / 20);
        $snum = $num * $limit;

        $data = [
            'first' => ['value' => "在“女神节”这个宠爱与被宠爱的节日，儿宝宝邀请了张兰萍主任为我们进行直播答疑，解读宫颈癌疫苗你想知道的那些事，同时在直播间更有HPV二价疫苗首针免费抽奖，九价疫苗预约指引，姐妹们一起冲鸭。"],
            'keyword1' => ARRAY('value' => '迎女神节福利：专家直播答疑，抽奖免费送HPV二价首针，九价疫苗预约指引'),
            'keyword2' => ARRAY('value' => '2022年3月8日 晚19点'),
            'remark' => ARRAY('value' => ""),
        ];
        $url='https://kfl.h5.xeknow.com/sl/3qJCnD';
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', $url);
        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->andWhere(['type'=>0])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {

            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', $url);
            var_dump($v);
        }
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', $url);
        exit;
        $doctors=UserDoctor::find()->select('userid')->where(['county'=>1106])->column();

        $doctorParent=DoctorParent::find()
            ->select('parentid')
            ->leftJoin('pregnancy', '`pregnancy`.`familyid` = `doctor_parent`.`parentid`')
            ->andWhere(['>', 'pregnancy.familyid', 0])
            ->andWhere(['doctor_parent.doctorid'=>$doctorid])
            ->column();

        $autograph=Autograph::find()->where(['in','userid',$doctorParent])->andWhere(['doctorid'=>$doctorid])->orderBy('doctorid')->all();

        foreach($autograph as $k=>$v){
            $ud=UserDoctor::findOne(['userid'=>$v->doctorid]);
            $Pregnancy=Pregnancy::find()->where(['familyid'=>$v->userid])->one();
            $phone=UserLogin::getPhone($v->userid);
            if($phone) {
                $rs = [];
                $rs[] = $ud->hospital->name;
                $rs[] = $Pregnancy->field1;
                $rs[] = date('Y-m-d', $v->createtime);
                $rs[] = UserLogin::getPhone($v->userid);
                echo implode(',', $rs);
                echo "\n";
            }
        }
        exit;
        $totle = 412976;
        $limit = ceil($totle / 50);
        $snum = $num * $limit;

        $data = [
            'first' => ['value' => "妇科炎症是女性的常见疾病，主要是指女性生殖器官的炎症（外阴炎、阴道炎、宫颈炎、子宫炎、盆腔炎、附件炎、性传播疾病等），女性的生殖器官通常发生不同的急性和慢性炎症，在受到各种致病菌侵袭感染后发生。我们该如何进行产后妇科炎症预防及处理呢？为此儿宝宝特邀，北京市右安门医院李小萍妇科主任来和我们聊聊，“产后妇科炎症的预防及处理”。"],
            'keyword1' => ARRAY('value' => '产后妇科炎症的预防及处理，第二十九期健康直播课即将开始'),
            'keyword2' => ARRAY('value' => '2021年11月17日 15点'),
            'remark' => ARRAY('value' => ""),
        ];
        $url='https://appsx0v9q8i8331.h5.xiaoeknow.com/v2/course/alive/l_6193d856e4b07ededa9e40b2?app_id=appsx0v9q8I8331&alive_mode=0&pro_id=&type=2';
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', $url);
        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->andWhere(['type'=>0])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {

            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', $url);
            var_dump($rs);
            usleep(250000);
        }
        var_dump($login);
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', $url);
        exit;

        $childs=[5101
            ,5459
            ,5891
            ,6182
            ,8800
            ,13552
            ,13557
            ,13708
            ,13781
            ,14051
            ,14218
            ,14267
            ,14270
            ,14278
            ,16174
            ,16176
            ,16214
            ,16498
            ,16499
            ,16573
            ,16806
            ,17154
            ,20715
            ,20728
            ,25881
            ,28425
            ,29490
            ,30258
            ,30260
            ,30261
            ,31104
            ,31188
            ,31211
            ,31660
            ,31663
            ,32025
            ,32032
            ,32362
            ,32364
            ,32367
            ,33164];
        $child=AppointAdult::find()->where(['in','id',$childs])->all();
        foreach($child as $k=>$v){
            echo $v->id;
            echo $v->name;
            echo "\n";
        }
        exit;
        $appoint=Appoint::find()->select('count(*) as a')->where(['doctorid'=>175877])->andWhere(['in','vaccine',[57,58,59]])->andWhere(['!=','state',3])->groupBy('childid')->createCommand()->getRawSql();
        var_dump($appoint);exit;
        foreach($appoint as $k=>$v){
            var_dump($v);
        }
        exit;
        $userDoctor=UserDoctor::find()->all();
        foreach($userDoctor as $k=>$v){
            $hospital=Hospital::findOne(['id'=>$v->hospitalid]);
            $rs=[];
            $rs[]=$hospital->name;
            $rs[]=Area::$all[$hospital->county];
            echo implode(',',$rs);
            echo "\n";
        }
        exit;


        $appoint = HospitalAppoint::findOne(['doctorid' => 400564, 'type' => 2]);

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
            $appointList=new AppointList(400564,2);

            $vaccines = $vQuery->asArray()->all();
            $delay = $appoint->delay;
            foreach ($vaccines as $k => $v) {
                $rs = $v;
                $rs['name'] = $rs['name'] . "【" . Vaccine::$typeText[$rs['type']] . "】";
                $rows[] = $rs;
                $day = strtotime(date('Y-m-d', strtotime('+' . $delay . " day")));
                for ($i = 1; $i <= 60; $i++) {
                    $day = $day + 86400;

                    $appointList->setVaccineNum($v['id'],date('Y-m-d',$day));
                }

            }
        }
        exit;

        $count=ArticleUser::find()->where(['userid'=>206260])->andWhere(['>','createtime','1609430400'])->andWhere(['<','createtime','1627660800'])->count();
        var_dump($count);exit;

        $doctorParent=DoctorParent::find()->where(['doctorid'=>206260])->select('parentid')->column();
        $count=Points::find()->where(['in','userid',$doctorParent])->andWhere(['source'=>3])->andWhere(['>','createtime','1609430400'])->andWhere(['<','createtime','1627660800'])->count();

        var_dump($count);exit;

        $totle = 391347;
        $limit = ceil($totle / 50);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => "2021年8月1日至7日是全球第30个“世界母乳喂养周”，其主题是“保护母乳喂养，共同承担责任”。为此儿宝宝邀请了海淀妇幼李海苗主任来为我们答疑解惑。"],
                'keyword1' => ARRAY('value' => '哺乳期常见乳房问题及应对措施，第二十七期健康直播课即将开始'),
                'keyword2' => ARRAY('value' => '2021年08月08日 15点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v2/course/alive/l_610e0830e4b054ed7c4b0ac6?app_id=appsx0v9q8I8331&alive_mode=0&pro_id=&type=2');
            var_dump($rs);
            sleep(1);
        }
        var_dump($login);
        $rs = WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v2/course/alive/l_610e0830e4b054ed7c4b0ac6?app_id=appsx0v9q8I8331&alive_mode=0&pro_id=&type=2');
        exit;

//        $child=ChildInfo::find()->where(['>','birthday',strtotime('-1 year')])->count();
//        var_dump($child);exit;
//        $child=DoctorParent::find()
//            ->leftJoin('child_info', '`doctor_parent`.`parentid` = `child_info`.`userid`')
//            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
//            //->andFilterWhere(['>','child_info.birthday',strtotime('-6 year')])
//            ->andFilterWhere(['!=','`doctor_parent`.`doctorid`', 213579])
//            ->andFilterWhere(['child_info.source'=>110602])
//            ->count();
        $child = ChildInfo::find()
            ->select('userid')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            //->andFilterWhere(['>','child_info.birthday',strtotime('-10 year')])
            //->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->count();
        var_dump($child);
        exit;

        $child = DoctorParent::find()
            ->leftJoin('child_info', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['>', 'child_info.birthday', strtotime('-1 year')])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => 47156])
            ->andFilterWhere(['child_info.source' => 110602])
            ->all();
        foreach ($child as $k => $v) {
            $v->doctorid = 213579;
            $v->save();
        }

        var_dump($child);
        exit;


        $data = [
            'first' => array('value' => "您好家长，您的宝宝如果还没有进行常规体检，请您在一周之内带孩子到保健科体检"),
            'keyword1' => ARRAY('value' => '社区健康体检',),
            'keyword2' => ARRAY('value' => '一周内'),
            'remark' => ARRAY('value' => "体检时间查看预防保健科门诊日。如已体检，请忽略。注意：如有超期不再体检。（儿童体检时间表：出生后第42天、3月龄、6月龄、9月龄、12月龄、18月龄、2周岁、2岁6月龄、3周岁）", 'color' => '#221d95'),
        ];
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/user/examination/index?id=1",
        ];
        WechatSendTmp::send($data, 'o5ODa0451fMb_sJ1D1T4YhYXDOcg', 'b1mjgyGxK-YzQgo3IaGARjC6rkRN3qu56iDjbD6hir4', '', $miniprogram);
        exit;
        $doctorid = 206260;
        $dname = '新村社区';
        //签约儿童总数
        $child = ChildInfo::find()
            ->select('userid')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['>', 'child_info.birthday', strtotime('-6 year')])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->column();

        $pregLCount = Pregnancy::find()
            ->select('familyid')
            ->andWhere(['pregnancy.field49' => 0])
            ->andWhere(['>', 'pregnancy.field16', strtotime('-43 week')])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])->column();

        $array = $child + $pregLCount + [390512, 175579];
        //$array=[390512,175579];
        $data = [
            'first' => array('value' => "欢迎大家加入【儿宝宝{$dname}妈妈交流群】，我们的宝宝同在{$dname}医院接种疫苗和体检，所以看到老相识不要太惊喜哟",),
            'keyword1' => ARRAY('value' => "儿宝宝用户"),
            'keyword2' => ARRAY('value' => date('Y年m月d H:i')),
            'keyword3' => ARRAY('value' => "请您点击查看详情，并长按二维码进入【{$dname}妈妈交流群】"),

            'remark' => ARRAY('value' => "基于线下的真实社群，为您打造社区医院的助手服务及全方位综合母婴服务，力求提高您的满意度。群内服务包括：社区医院政策宣传、疫苗及体检咨询、儿科医生咨询、科学育儿指导、孕育知识分享、妈妈经验交流、社区亲子活动等等。", 'color' => '#221d95'),
        ];
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=1",
        ];
        $temp = 'Pa_dWDnwfS5FYpQmB8wf5uWyge50tGpxfg47xfGLYrI';
        foreach ($array as $k => $v) {
            $login = UserLogin::find()->where(['!=', 'openid', ''])->andWhere(['userid' => $v])->one();
            if ($login) {
                print_r($login->openid);
                $rs = WechatSendTmp::send($data, $login->openid, $temp, "http://child.wedoctors.com.cn/hospital/{$doctorid}.html");
                if ($rs) {
                    echo "==true";
                } else {
                    echo "==false";
                }
                echo "\n";
            }


        }
        exit;

        $totle = 358676;
        $limit = ceil($totle / 50);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => "从前几期“母乳喂养那些事”我们也帮助很多宝妈们了解到了母乳喂养的困难以及如何解决的办法，但是为了让家长更好的了解到母乳喂养以及如何护理乳房保健，我们邀请到了海淀区妇幼保健院妇产科主管护师龙凤君来给我们宝宝妈妈们进行讲解。"],
                'keyword1' => ARRAY('value' => '母乳喂养与乳房保健，第二十六期健康直播即将开始'),
                'keyword2' => ARRAY('value' => '2021年05月23日 15点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8I8331.h5.xiaoeknow.com/v2/course/alive/l_60a8b5c2e4b0c726421804e2?app_id=appsx0v9q8I8331&alive_mode=0&pro_id=&type=2');
            var_dump($rs);
            sleep(1);
        }
        var_dump($login);
        exit;


        $data = [
            'first' => array('value' => "尊敬的用户您好，由于因中心停电整修，请5月7日上午疫苗接种的用户于9:30-11:00来本中心预防保健科进行接种，给您带来不便请谅解，感谢您的理解和支持。\n",),
            'keyword1' => ARRAY('value' => "儿宝宝用户"),
            'keyword2' => ARRAY('value' => date('Y年m月d H:i')),
            'keyword3' => ARRAY('value' => '请您于9:30-11:00前往社区接种'),

            'remark' => ARRAY('value' => " ", 'color' => '#221d95'),
        ];

        $temp = 'Pa_dWDnwfS5FYpQmB8wf5uWyge50tGpxfg47xfGLYrI';


        $appoint = Appoint::find()->where(['doctorid' => 160226])->andWhere(['appoint_date' => 1620316800])->andWhere(['in', 'appoint_time', [1, 2, 3, 7, 8, 9, 10, 11, 12, 19, 20]])->all();
        foreach ($appoint as $K => $v) {
            $userLogin = UserLogin::findAll(['userid' => $v->userid]);
            if ($userLogin) {
                foreach ($userLogin as $ulk => $ulv) {
                    if ($ulv->openid) {
                        $rs = WechatSendTmp::send($data, $ulv->openid, $temp);
                        echo $rs ? 'true' : 'false';
                        echo "\n";
                    }
                }
            }
        }
        exit;


        ini_set('memory_limit', '4000M');

        $s_time = '20201001';
        $e_time = '20210101';


        foreach (Area::$county[11] as $k => $v) {
            $rs = [];
            $userDoctors = UserDoctor::find()->where(['county' => $k])->select('userid')->column();

            if ($userDoctors) {
                $doctorParents2 = DoctorParent::find()->where(['in', 'doctorid', $userDoctors])
                    ->select('parentid')
                    ->column();


                $userLogin = UserLogin::find()->select('openid')->where(['in', 'userid', $doctorParents2])->andWhere(['!=', 'openid', ''])->groupBy('userid')->column();
                $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1369])->groupBy('openid')->count();
                $r1 = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1369])->groupBy('openid')->andWhere(['level' => 1])->count();
                $rs[] = $r1 + round($r1 * 0.30);

                //$userLogin=UserLogin::find()->select('openid')->where(['in','userid',$childs])->andWhere(['!=','openid',''])->groupBy('userid')->column();
                $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1370])->groupBy('openid')->count();
                $r2 = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1370])->groupBy('openid')->andWhere(['level' => 1])->count();
                $rs[] = $r2 + round($r2 * 0.20);

                echo $v . "," . $rs[0] . "," . $rs[1] . "," . $rs[2] . "," . $rs[3];
                echo "\n";
            }
        }

        exit;

        $HealthRecords = HealthRecordsSchool::find()->where(['doctorid' => 400564])->all();
        foreach ($HealthRecords as $k => $v) {
            $hr = HealthRecords::find()->where(['field30' => $v->id])->all();
            foreach ($hr as $hk => $kv) {
                echo "wkhtmltopdf http://child.wedoctors.com.cn/health-records/down?userid={$kv->userid} {$v->id}/{$kv->field29}.pdf";
                echo "\n";
            }
        }
        exit;


        $userDoctor = UserDoctor::findAll(['county' => 1106]);
        $dates = [
            10 => ['s' => '2020-10-01', 'e' => '2020-10-31'],
            11 => ['s' => '2020-11-01', 'e' => '2020-11-30'],
            12 => ['s' => '2020-12-01', 'e' => '2020-12-31'],
            1 => ['s' => '2021-01-01', 'e' => '2021-01-31'],
            2 => ['s' => '2021-02-01', 'e' => '2021-02-29'],
            3 => ['s' => '2021-03-01', 'e' => '2021-03-31'],


        ];
        foreach ($userDoctor as $k => $v) {
            $rs = [];
            $rs[] = $v->name;
            foreach ($dates as $dk => $dv) {
                $s = strtotime($dv['s']);
                $e = strtotime($dv['e']);
                $num = ArticleUser::find()->where(['>=', 'createtime', $s])->andWhere(['<=', 'createtime', $e])->andWhere(['userid' => $v->userid])->count();
                $rs[] = $num;
            }
            echo implode(',', $rs);
            echo "\n";
        }
        exit;


        $totle = 315429;
        $limit = ceil($totle / 100);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => "宝宝新陈代谢旺盛，皮肤发育不成熟，需要家长精心呵护。作为妈妈，你知道宝宝的皮肤有哪些特点吗？一年四季，该如何护理宝宝的皮肤？需要注意哪些关键点？宝宝皮肤护理有哪些误区？怎么样预防常见的皮肤疾病呢？儿宝宝特意为已经到来的春季邀请到了梁源博士，来为家长讲讲宝宝娇嫩的皮肤该如何护理"],
                'keyword1' => ARRAY('value' => '儿童医院梁主任教大家正确护理宝宝皮肤，预防常见皮肤疾病 第二十五期健康直播'),
                'keyword2' => ARRAY('value' => '2021年04月21日 20点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8I8331.h5.xiaoeknow.com/v2/course/alive/l_607ecea8e4b09134c98a15ec?app_id=appsx0v9q8I8331&alive_mode=0&pro_id=&type=2');
            var_dump($rs);
            sleep(1);
        }
        var_dump($login);
        exit;


        $doctorParent = DoctorParent::find()->select('count(*) as a,teamid')->where(['doctorid' => 206262])->groupBy('teamid')->orderBy('a desc');

        var_dump($doctorParent->createCommand()->getSql());
        exit;
        $doctorParent = DoctorParent::findAll(['doctorid' => 206262]);
        foreach ($doctorParent as $k => $v) {
            var_dump($v->teamid);
        }
        exit;
//
//        $totle = 338695;
//        $limit = ceil($totle / 100);
//        $snum = $num * $limit;
//
//        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
//        foreach ($login as $k => $v) {
//            $data = [
//                'first' => ['value' => "6岁以下的儿童处于快速生长发育阶段，特别是0~3岁的儿童生长发育得更快，营养与儿童的生长发育有着密不可分的关系，是评价儿童健康发育的一个重要指标，儿童生长发育越快，需要从膳食中摄取的营养也很越多，营养是保证正常生长发育，促进身心健康的重要因素。"],
//                'keyword1' => ARRAY('value' => '儿童营养健康和科学运动，第二十四期健康直播即将开始'),
//                'keyword2' => ARRAY('value' => '2021年02月07日 16点'),
//                'remark' => ARRAY('value' => ""),
//            ];
//            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8I8331.h5.xiaoeknow.com/v2/course/alive/l_6062d02de4b008d70f07b08a?app_id=appsx0v9q8I8331&alive_mode=0&pro_id=&type=2');
//            var_dump($rs);
//            sleep(1);
//        }
//        var_dump($login);
//        exit;


        var_dump(array_unique($array));
        exit;


        $file = fopen('shengao.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $rs = explode(',', trim($line));
            $count = Examination::find()
                ->where(['field2' => $rs[2], 'field3' => $rs[3], 'field32' => ChildInfo::$genderText[$rs[1]]])
                ->andWhere(['<', 'field40', $rs[4]])->count();
            echo trim($line) . "," . $count . "\n";
        }
        exit;


        $sdate = '2020-01-01';

        for ($i = 0; $i < 12; $i++) {
            $date = strtotime("+$i month", strtotime($sdate));
            $j = $i + 1;
            $edate = strtotime("+$j month", strtotime($sdate));

            $pregLCount = ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andWhere(['>=', 'doctor_parent.createtime', $date])
                ->andWhere(['<', 'doctor_parent.createtime', $edate])
                ->count();
            $rs = [];
            $rs[] = date('Y-m', $date);
            $rs[] = $pregLCount;
            echo implode(',', $rs);
            echo "\n";
        }
        exit;


        $parentids = \common\models\DoctorParent::find()->select('parentid')->andFilterWhere(['`doctor_parent`.`doctorid`' => 156256])->andFilterWhere(['level' => 1])->column();

        $query = ChildInfo::find();
        $query->select('userid');
        $query->andFilterWhere(['>', '`child_info`.birthday', strtotime("-6 year")]);
        $query->andFilterWhere(['not in', '`child_info`.userid', $parentids]);
        $query->andFilterWhere(['`child_info`.`admin`' => 110588]);
        $list = $query->all();
        foreach ($list as $k => $v) {
            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid]);
            if ($doctorParent) {
                $doctorParent->doctorid = 156256;
                $doctorParent->save();
            }
        }
        exit;


//        $userDoctors = UserDoctor::find()->where(['city' => 11])->all();
//        foreach ($userDoctors as $k => $v) {
//
//            $appoint = HospitalAppoint::findOne(['doctorid' => $v->userid, 'type' => 2]);
//            $userDoctor = UserDoctor::findOne(['userid' => $v->userid]);
//            if ($userDoctor->appoint) {
//                $types = str_split((string)$userDoctor->appoint);
//            }
//            if ($appoint && in_array(2, $types)) {
//                $t='已开通';
//            }else{
//                $t="未开通";
//            }
//
//            echo $v->name.','.$v->hospital->name.",".Area::$all[$v->county].",".$t;
//            echo "\n";
//        }
//        exit;

//        $file = fopen('110588.csv', 'r');
//        while (($line = fgets($file)) !== false) {
//            $row = explode(',', trim($line));
//
//            $name = $row[0];
//            $birthday = $row[1];
//            $child = ChildInfo::findOne(['name' => $name, 'birthday' => strtotime($birthday), 'doctorid' => 110595]);
//            if ($child) {
//                $auto = Autograph::findOne(['userid' => $child->userid]);
//                if ($auto) {
//                    $auto->doctorid = 192821;
//                    $auto->save();
//                }
//                $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
//                if ($doctorParent) {
//                    $doctorParent->createtime=time();
//                    $doctorParent->doctorid = 192821;
//                    $doctorParent->save();
//                }
//                $child->doctorid = 110595;
//                if ($child->admin){
//                    $child->admin = 110595;
//                }
//                $child->save();
//                var_dump($name);
//
//            } else {
//                var_dump("");
//            }
//        }
//        exit;


        $totle = 315429;
        $limit = ceil($totle / 100);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => "人类婴幼儿同样存在类似的关键时期现象，也就是很多能力必须在特定时期得到环境获得发展，错过了这个时期就会影响发展的水平，甚至失去发展的可能，就像大家曾经听说过的“狼孩儿’、“猪孩儿”的故事。宝宝0-3岁是儿童早期发展的重要时期，为此儿宝宝邀请了闫琦主任来和我们聊聊宝宝早期发展那些事。"],
                'keyword1' => ARRAY('value' => '关注0-3岁儿童早期发展，第二十三期健康直播即将开始'),
                'keyword2' => ARRAY('value' => '2021年02月07日 16点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v1/course/alive/l_601e3014e4b0f176aed09db8?type=2');
            var_dump($rs);
            sleep(1);
        }
        var_dump($login);
        exit;


        Notice::setList(390512, 3, ['title' => 'sdfasdfasdfasdf', 'ftitle' => '一，二月龄宝宝家长', 'id' => "/pages/article/view/index?id=1370"]);
        exit;
        $s_time = '20201101';
        $e_time = '20201201';


        $userDoctors = UserDoctor::find()->all();
        foreach ($userDoctors as $k => $v) {
            $rs = [];
            $rs[] = $v->county;

            $doctorParents2 = DoctorParent::find()->where(['doctorid' => $v->userid])
                ->select('parentid')
                ->column();


            $userLogin = UserLogin::find()->select('openid')->where(['in', 'userid', $doctorParents2])->andWhere(['!=', 'openid', ''])->groupBy('userid')->column();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1369])->groupBy('openid')->count();
            $r1 = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1369])->groupBy('openid')->andWhere(['level' => 1])->count();
            $rs[] = $r1 + round($r1 * 0.30);

            //$userLogin=UserLogin::find()->select('openid')->where(['in','userid',$childs])->andWhere(['!=','openid',''])->groupBy('userid')->column();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1370])->groupBy('openid')->count();
            $r2 = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['<', 'createtime', strtotime($e_time)])->andWhere(['aid' => 1370])->groupBy('openid')->andWhere(['level' => 1])->count();
            $rs[] = $r2 + round($r2 * 0.20);

            echo $v->name . "," . ($rs[1] + $rs[3]) . "," . ($rs[2] + $rs[4]);
            echo "\n";
        }

        exit;


        $field = fopen('110595.csv', 'r');
        while (($line = fgets($field)) !== false) {
            $row = explode(',', trim($line));
            $name = $row[0];
            $birthday = $row[1];
            $child = ChildInfo::findOne(['name' => $name, 'birthday' => strtotime($birthday), 'doctorid' => 110595]);
            if ($child) {
                $row[2] = "已查询";
                $auto = Autograph::findOne(['userid' => $child->userid]);
                if ($auto) {
                    $row[3] = "已签字";
                } else {
                    $row[3] = "未查询到签字";
                }
                $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
                if ($doctorParent) {
                    $row[4] = "已签约";
                } else {
                    $row[4] = "未查询到签约";
                }
            } else {
                $row[2] = "未查询到";
            }
            echo implode(',', $row);
            echo "\n";
        }
        exit;
        $list = FileHelper::findFiles('data/qingta');
        foreach ($list as $k => $v) {
            if ($v == 'data/qingta/.DS_Store') continue;

        }
        exit;


        $a = 0;
        for ($i = 1; $i < 8; $i++) {
            $file = file_get_contents('data/' . $i . '.txt');
            $data = json_decode($file, true);
            if ($data) {
                foreach ($data['words_result'] as $k => $v) {

                    if ($k % 2 == 0) {
                        $rs['name'] = $v['words'];
                    } else {
                        $rs['time'] = $v['words'];
                        $row[] = $rs;
                        $rs = [];
                    }
                }
            }
        }

        foreach ($row as $k => $v) {
            $child = ChildInfo::find()->where(['name' => $v['name'], 'birthday' => strtotime($v['time'])])
                ->andWhere(['or', 'doctorid=110595', 'source=110595'])->one();
            if ($child) {
                $a++;
            }
        }
        var_dump($a);
        exit;


        $totle = 707218;
        $limit = ceil($totle / 50);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => '2020年11月5日，中国保险行业协会、中国医师协会联合在京举办新闻通气会，正式发布《重大疾病保险的疾病定义使用规范（2020年修订版）》。这是保险业协会和医师协会继2007年制定发布《重大疾病保险的疾病定义使用规范》后，在中国银保监会指导下，再度合作开展修订工作。'],
                'keyword1' => ARRAY('value' => '2020重大疾病险新规政策解读，第十九期健康直播即将开始'),
                'keyword2' => ARRAY('value' => '2020年12月30日下午3点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v1/course/alive/l_5feaed31e4b04db7c097ce6a?type=2');
            sleep(1);
        }
        var_dump($login);
        exit;


        $totle = 282750;
        $limit = ceil($totle / 50);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->orderBy('id desc')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => '怎么样能判断什么样的小问题会对宝宝大健康有影响呢？为了帮助家长了解这些小问题，我们邀请了首都医科大学附属北京儿童医院儿内科知名专家刘小梅。'],
                'keyword1' => ARRAY('value' => '北京儿童医院 小梅主任教您关注小问题，维护大健康，第十八期健康直播即将开始'),
                'keyword2' => ARRAY('value' => '2020年12月24日下午3点半'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v1/course/alive/l_5fe2f2e2e4b04db7c0969bab?type=2');
            sleep(1);
        }
        var_dump($login);
        exit;
        $file = fopen('110588.csv', 'r');
        while (($line = fgets($file)) !== false) {
            $row = explode(',', trim($line));
            $name = $row[0];
            $birthday = $row[3];
            $child = ChildInfo::findOne(['name' => $name, 'birthday' => strtotime($birthday), 'doctorid' => 110588]);
            if ($child) {
                $auto = Autograph::findOne(['userid' => $child->userid]);
                if ($auto) {
                    $auto->doctorid = 386661;
                    $auto->save();
                }
                $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
                if ($doctorParent) {
                    $doctorParent->doctorid = 386661;
                    $doctorParent->save();
                }
                $child->doctorid = 110645;
                $child->save();

            } else {
                var_dump("");
            }
        }
        exit;


        $doctorParent = DoctorParent::findAll(['doctorid' => 400564]);
        foreach ($doctorParent as $k => $v) {
            $openid = UserLogin::getOpenid($v->parentid);
            $child = ChildInfo::find()->where(['userid' => $v->parentid])->andWhere(['>', 'userid', 405669])->andWhere(['field27' => ''])->andWhere(['idcard' => ''])->one();

            echo $v->parentid;
            if ($openid && $child) {
                echo "f";
                $data = [
                    'first' => array('value' => '八里庄社区卫生服务中心提醒您完善宝宝信息'),
                    'keyword1' => ARRAY('value' => date('Y年m月d H:i')),
                    'keyword2' => ARRAY('value' => '各位家长：为了更好的为签约儿童提供签约管理服务，现需要各位家长完善儿童身份证号码或儿童医学编码信息（六个月以下，没有身份证号的儿童），请各位家长按照以下说明完善信息即可，感谢您的支持。八里庄社区卫生服务中心预防保健科'),
                    'remark' => ARRAY('value' => "点击查看信息完善说明！", 'color' => '#221d95')
                ];

                $miniprogram = [
                    "appid" => \Yii::$app->params['wxXAppId'],
                    "pagepath" => "/pages/article/view/index?id=1484",
                ];
                WechatSendTmp::send($data, $openid, 'AisY28B8z8_UDjX7xi6pay7Hh6kw420rAQwc6I1BBtE', '', $miniprogram);
            }
            echo "\n";

        }
        exit;


        $auto = Autograph::find()->select('userid')->where(['doctorid' => 206260])->column();
        $child = ChildInfo::find()
            ->andFilterWhere(['in', '`child_info`.`userid`', array_unique($auto)])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
            ->all();
        foreach ($child as $k => $v) {
            $rs = [];
            $rs[] = $v->name;
            $rs[] = "\t" . $v->idcard;
            $au = Autograph::findOne(['userid' => $v->userid]);

            $rs[] = date('Y-m-d', $au->createtime);
            $userParent = UserParent::findOne(['userid' => $v->userid]);
            if ($userParent && $userParent->mother_phone) {
                $rs[] = "\t" . $userParent->mother_phone;
            } else {
                $rs[] = "\t" . UserLogin::getPhone($v->userid);
            }
            echo implode(',', $rs);
            echo "\n";
        }
        exit;

        $doctorids = [110627];
        $doctors = UserDoctor::find()->where(['in', 'hospitalid', $doctorids])->column();
        $doctorParent = DoctorParent::find()->select('parentid')->where(['in', 'doctorid', $doctors])->column();

        $child = ChildInfo::find()->where(['in', 'source', $doctorids])->andWhere(['>', 'birthday', strtotime('-3 year')])->andWhere(['not in', 'userid', $doctorParent])->all();
        foreach ($child as $k => $v) {
            $rs = [];
            $hospital = Hospital::findOne($v->source);
            $userParent = UserParent::findOne(['userid' => $v->userid]);
            if ($userParent && $userParent->mother_phone) {
                $rs[] = $userParent->mother_phone;
                $rs[] = $hospital->name;

                echo implode(',', $rs);
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
                    $da = explode(' ', $rs[2]);
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


        $appoint = Appoint::find()->where(['doctorid' => 113890])->andWhere(['!=', 'state', 3])->all();
        foreach ($appoint as $k => $v) {
            if ($v->type == 4 || $v->type == 7) {
                if ($v->childid) {
                    $name = \common\models\AppointAdult::findOne(['id' => $v->childid])->name;
                } else {
                    $name = \common\models\AppointAdult::findOne(['userid' => $v->userid])->name;
                }

            } elseif ($v->type == 5 || $v->type == 6) {
                $name = \common\models\Pregnancy::findOne(['id' => $v->childid])->field1;
            } else {
                $child = \common\models\ChildInfo::findOne(['id' => $v->childid]);
                if ($child) {
                    $name = $child->name;
                }
            }
            $row = [];
            $row[] = $name;
            $row[] = $v->phone;
            $row[] = \common\models\Appoint::$typeText[$v->type];

            $row[] = date('Y-m-d', $v->appoint_date);
            $row[] = \common\models\Appoint::$timeText[$v->appoint_time];
            if ($v->vaccine == -2) {
                $row[] = "两癌筛查";
            } elseif ($v->vaccine) {
                $row[] = \common\models\Vaccine::findOne($v->vaccine)->name;
            } else {
                $row[] = "";
            }
            echo implode(',', $row);
            echo "\n";
        }


        ini_set('memory_limit', '6000M');
        $totle = 282750;
        $limit = ceil($totle / 100);
        $snum = $num * $limit;

        $login = UserLogin::find()->select('openid')->where(['!=', 'openid', ''])->groupBy('openid')->offset($snum)->limit($limit)->column();
        foreach ($login as $k => $v) {
            $data = [
                'first' => ['value' => '秋冬季节是呼吸道传染病的流行季节，我们如何做好个人防护呢？在践行健康文明的生活方式里，在防控新冠肺炎流行的同时怎么能同时抵御流感等其他呼吸道传染病的威胁呢？不要着急，本期健康直播有幸邀请到孙瑛博士为您详细讲解。'],
                'keyword1' => ARRAY('value' => '秋冬季流行疾病预防及疫情防控，第十五期健康直播即将开始'),
                'keyword2' => ARRAY('value' => '2020年10月04日上午9点'),
                'remark' => ARRAY('value' => ""),
            ];
            $rs = WechatSendTmp::send($data, $v, 'NNm7CTQLIY66w3h4FzSrp_Lz54tA12eFgds07LRMQ8g', 'https://appsx0v9q8i8331.h5.xiaoeknow.com/v1/course/alive/l_5fa124f0e4b01f764d887635?type=2');
            sleep(1);
        }
        var_dump($login);
        exit;

        //$hospitalAppoint=HospitalAppoint::find()->select('doctorid')->where(['type'=>4])->column();
        $query = UserDoctor::find()->where(['like', 'appoint', 4]);

        $doctors = $query->orderBy('appoint desc')->all();


        foreach ($doctors as $k => $v) {
            $name = Hospital::findOne($v->hospitalid)->name;
            echo $name . "\n";

        }
        exit;

        $appoint = Appoint::find()->select('phone')->where(['doctorid' => 160226, 'state' => 1, 'type' => 4])->andWhere(['in', 'vaccine', [56, 55, 54, 0]])->column();
        foreach ($appoint as $k => $v) {
            $rs = [];

            $rs[] = $v;
            $rs[] = "西罗园社区卫生服务中心";
            $rs[] = "四价宫颈癌疫苗";
            $rs[] = "010-87289908";

            echo implode(',', $rs);
            echo "\n";
        }
        exit;
        $s_time = '20201001';
        $e_time = '20201026';


        $userDoctors = UserDoctor::find()->all();
        foreach ($userDoctors as $k => $v) {
            $rs = [];
            $rs[] = $v->county;

            $doctorParents2 = DoctorParent::find()->where(['doctorid' => $v->userid])
                ->select('parentid')
                ->column();


            $userLogin = UserLogin::find()->select('openid')->where(['in', 'userid', $doctorParents2])->andWhere(['!=', 'openid', ''])->groupBy('userid')->column();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['aid' => 1369])->groupBy('openid')->count();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['aid' => 1369])->groupBy('openid')->andWhere(['level' => 1])->count();
            $rs[] = "";

            //$userLogin=UserLogin::find()->select('openid')->where(['in','userid',$childs])->andWhere(['!=','openid',''])->groupBy('userid')->column();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['aid' => 1370])->groupBy('openid')->count();
            $rs[] = ArticlePushVaccine::find()->where(['in', 'openid', $userLogin])->andWhere(['>=', 'createtime', strtotime($s_time)])->andWhere(['aid' => 1370])->groupBy('openid')->andWhere(['level' => 1])->count();
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