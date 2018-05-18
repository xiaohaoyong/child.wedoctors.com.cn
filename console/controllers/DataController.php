<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/5/2
 * Time: 上午11:32
 */

namespace console\controllers;


use callmez\wechat\sdk\components\BaseWechat;
use callmez\wechat\sdk\MpWechat;
use common\components\HttpRequest;
use common\helpers\WechatSendTmp;
use common\models\Area;
use common\models\BabyTool;
use common\models\BabyToolTag;
use common\models\ChatRecord;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Examination;
use common\models\Notice;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\Vaccine;
use common\models\WeOpenid;
use Faker\Provider\File;
use yii\base\Controller;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


class DataController extends Controller
{
    public function actionArticlePush(){
        exit;
        $article=\common\models\Article::findOne(200);

        $data = [
            'first' => array('value' => $article->info->title."\n",),
            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
            'keyword2' => ARRAY('value' =>'儿宝宝'),
            'keyword3' => ARRAY('value' =>'儿宝宝'),
            'keyword4' => ARRAY('value' =>'宝爸宝妈'),
            'keyword5' => ARRAY('value' =>$article->info->title),

            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
        $miniprogram=[
            "appid"=>\Yii::$app->params['wxXAppId'],
            "pagepath"=>"/pages/article/view/index?id=".$article->id,
        ];
        $userids=UserLogin::find()->where(['userid'=>'47388'])->all();

        if($article)
        {
            foreach($userids as $k=>$v) {

                $userLogin=$v;
                if($userLogin->openid) {
                    $rs=WechatSendTmp::send($data, $userLogin->openid, \Yii::$app->params['zhidao'],'',$miniprogram);
                }
                if($article->art_type!=2)
                {
                    $key=$article->catid==6?3:5;
                    Notice::setList($userLogin->userid, $key, ['title' => $article->info->title, 'ftitle' => date('Y年m月d H:i'), 'id' => "/article/view/index?id=".$article->id,]);
                }
            }
        }
    }

    public function actionTe(){
        $weOpenid=WeOpenid::find()->andFilterWhere(['level'=>1])->andFilterWhere(['>','createtime','1524067200'])->all();
        foreach($weOpenid as $k=>$v)
        {
            $user=UserLogin::findOne(['openid'=>$v->openid]);
            if($user) {
                $parentid = $user->userid;
                $doctorParent=DoctorParent::findOne(['parentid'=>$parentid]);
                if($doctorParent){
                    $doctorParent->parentid=$parentid;
                    $doctorParent->doctorid=$v->doctorid;
                    $doctorParent->createtime=$v->createtime;
                    $doctorParent->level=1;
                    $doctorParent->save();
                    echo $parentid;
                }
            }
        }
        exit;
    }
    public function actionText(){


        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=139.129.246.51;dbname=child_health',
            'username' => 'wedoctors_admin',
            'password' => 'trd7V37v3PXeU9vn',
        ]);
        $connection->open();

        $f=fopen("data/doctor_parent.sql",'r');
        $i=0;
        while(($line=fgets($f))!==false) {
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

        echo md5(md5("139110083832QH@6%3(87"));exit;

        for($i=1;$i<10;$i++)
        {
            echo $i."\n";
            $row=ChildInfo::getChildType($i);
            echo date('Y-m-d',$row['firstday'])."\n";
            echo date('Y-m-d',$row['lastday'])."\n";
        }exit;


        $text=[
            '2018-01-01',
            '2019-02-01',
            '2018-12-01',
            '2017-01-01'
        ];
        rsort($text);
        var_dump($text);
        exit;

        $curl=new HttpRequest('https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eptlQANnGdZaa0B61xqymbGJib67XqeOEufjIeUXXUx9CibrrAkic1JichlNr698cbfN7u8IEsGJEVic9g/0',true,2);
        echo $curl->get();exit;
        ini_set('memory_limit', '2048M');

        $child=ChildInfo::find()->all();
        foreach($child as $k=>$v)
        {
            $v->birthday= strtotime(date('Y-m-d',$v->birthday));
            $v->save();
            echo date('Y-m-d H:i:s',$v->birthday);
            echo "\n";
        }
    }
    /**
     * 体检数据
     */
    public function actionEx(){
        error_reporting(E_ALL & ~E_NOTICE);

        $file_list=glob("data/ex/*.csv");
        foreach($file_list as $fk=>$fv) {
            preg_match("#\d+#", $fv, $m);
            $hospitalid = $m[0];
            echo $hospitalid."\n";
            $f=fopen($fv,'r');
            $i=0;
            while(($line=fgets($f))!==false) {
                if($i==0) {$i++; continue;}
                $i++;
                echo $i."===";
                $row=explode(",",trim($line));

                $row[3]=substr($row[3],0,strlen($row[3])-11);
                $ex=Examination::find()->andFilterWhere(['field1'=>$row[0]])
                    ->andFilterWhere(['field2'=>$row[1]])
                    ->andFilterWhere(['field3'=>$row[2]])
                    ->andFilterWhere(['field4'=>$row[3]])
                    ->andFilterWhere(['source'=>$hospitalid])
                    ->andFilterWhere(['field19'=>$row[18]])->one();
                if($ex){ continue;}
                $isupdate=$ex?0:1;
                $ex=$ex?$ex:new Examination();

                $child=ChildInfo::find()->andFilterWhere(['name'=>trim($row[0])])
                    ->andFilterWhere(['birthday'=>strtotime($row[18])])
                    //->andFilterWhere(['source'=>$hospitalid])
                    ->one();
                echo $row[0];
                if(!$child){
                    echo "--儿童不存在";
                }else{
                    echo "--儿童存在";
                    $ex->childid=$child->id;
                }

                $ex->field1 =$row[0];
                $ex->field2 =$row[1];
                $ex->field3 =$row[2];
                $ex->field4 =$row[3];
                $ex->field5 =$row[4];
                $ex->field6 =$row[5];
                $ex->field7 =$row[6];
                $ex->field8 =$row[7];
                $ex->field9 =$row[8];
                $ex->field10=$row[9];
                $ex->field11=$row[10];
                $ex->field12=$row[11];
                $ex->field13=$row[12];
                $ex->field14=$row[13];
                $ex->field15=$row[14];
                $ex->field16=$row[15];
                $ex->field17=$row[16];
                $ex->field18=$row[17];
                $ex->field19=$row[18];
                $ex->field20=$row[19];
                $ex->field21=$row[20];
                $ex->field22=$row[21];
                $ex->field23=$row[22];
                $ex->field24=$row[23];
                $ex->field25=$row[24];
                $ex->field26=$row[25];
                $ex->field27=$row[26];
                $ex->field28=$row[27];
                $ex->field29=$row[28];
                $ex->field30=$row[29];
                $ex->field31=$row[30];
                $ex->field32=$row[31];
                $ex->field33=$row[32];
                $ex->field34=$row[33];
                $ex->field35=$row[34];
                $ex->field36=$row[35];
                $ex->field37=$row[36];
                $ex->field38=$row[37];
                $ex->field39=$row[38];
                $ex->field40=$row[39];
                $ex->field41=$row[40];
                $ex->field42=$row[41];
                $ex->field43=$row[42];
                $ex->field44=$row[43];
                $ex->field45=$row[44];
                $ex->field46=$row[45];
                $ex->field47=$row[46];
                $ex->field48=$row[47];
                $ex->field49=$row[48];
                $ex->field50=$row[49];
                $ex->field51=$row[50];
                $ex->field52=$row[51];
                $ex->field53=$row[52];
                $ex->field54=$row[53];
                $ex->field55=$row[54];
                $ex->field56=$row[55];
                $ex->field57=$row[56];
                $ex->field58=$row[57];
                $ex->field59=$row[58];
                $ex->field60=$row[59];
                $ex->field61=$row[60];
                $ex->field62=$row[61];
                $ex->field63=$row[62];
                $ex->field64=$row[63];
                $ex->field65=$row[64];
                $ex->field66=$row[65];
                $ex->field67=$row[66];
                $ex->field68=$row[67];
                $ex->field69=$row[68];
                $ex->field70=$row[69];
                $ex->field71=$row[70];
                $ex->field72=$row[71];
                $ex->field73=$row[72];
                $ex->field74=$row[73];
                $ex->field75=$row[74];
                $ex->field76=$row[75];
                $ex->field77=$row[76];
                $ex->field78=$row[77];
                $ex->field79=$row[78];
                $ex->field80=$row[79];
                $ex->field81=$row[80];
                $ex->field82=$row[81];
                $ex->field83=$row[82];
                $ex->field84=$row[83];
                $ex->field85=$row[84];
                $ex->field86=$row[85];
                $ex->field87=$row[86];
                $ex->field88=$row[87];
                $ex->field89=$row[88];
                $ex->field90=$row[89];
                $ex->field91=$row[90];
                $ex->field92=$row[91];
                $ex->source=$hospitalid;
                $ex->isupdate=$isupdate;
                $ex->save();
                if($ex->firstErrors)
                {
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
    public function actionExUpdate(){
        $ex=Examination::find()->andFilterWhere(['childid'=>58170])->andFilterWhere(['isupdate'=>1])->andFilterWhere(['>','childid','0'])->all();
        foreach($ex as $k=>$v)
        {
            $child=ChildInfo::findOne(['id'=>$v->childid]);
            if($child) {
                $login = $child->login;
                if($login->openid){
                    $data = [
                        'first' => array('value' => "您好，宝宝近期的体检结果已更新\n",),
                        'keyword1' => ARRAY('value' =>$child->name),
                        'keyword2' => ARRAY('value' =>"身高:{$v->field40},体重:{$v->field70},头围:{$v->field80}"),
                        'keyword3' => ARRAY('value' =>$v->field9),
                        'keyword4' => ARRAY('value' =>$v->field4),
                        'remark' => ARRAY('value' => "\n 点击可查看本体检报告的详细内容信息", 'color' => '#221d95'),
                    ];
                    $miniprogram=[
                        "appid"=>\Yii::$app->params['wxXAppId'],
                        "pagepath"=>"/pages/user/examination/index?id=".$child->id,
                    ];
                    $rs=WechatSendTmp::send($data, $login->openid, \Yii::$app->params['tijian'],'',$miniprogram);
                    //小程序首页通知
                    Notice::setList($login->userid, 1, ['title' => "宝宝近期的体检结果已更新", 'ftitle' => "点击可查看本体检报告的详细内容信息", 'id' => "/user/examination/index?id=".$child->id,],$child->id);

                }
            }
        }
    }
    public function actionGetUid()
    {
        $wechat = new MpWechat([
            'token' => 'UWCE9B33CYcjaHFodunQPGCFFvfbd2Yz',
            'appId' => 'wx1147c2e491dfdf1d',
            'appSecret' => '98001ba41e010dea2861f3e0d95cbb15',
            'encodingAesKey' => '1ktMUR9QDYv4TZNh3dr7x6KWiymVXJRysSSrZ4oWMW7'
        ]);
        $access_token=$wechat->getAccessToken();
        $user=UserLogin::find()->where(['!=','openid',''])->orderBy('userid desc')->all();
        foreach($user as $k=>$v) {
            $userLogin=UserLogin::findOne(['userid'=>$v->userid]);
            $openid = $userLogin->openid;
            $path = '/cgi-bin/user/info?access_token='.$access_token."&openid=".$openid."&lang=zh_CN";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'].$path, true, 2);
            $userJson = $curl->get();
            $userInfo=json_decode($userJson,true);
            if($userInfo['unionid']) {
                $userLogin->unionid = $userInfo['unionid'];
                $userLogin->save();
                echo $v->userid."成功";
            }else{
                echo $v->userid."没有";

            }
            echo "\n";
        }

    }
    public function actionSet()
    {
        $userParent=UserParent::find()->andFilterWhere(['in','source',[110565]])->all();
        foreach($userParent as $k=>$v)
        {
            echo "parentid=".$v->userid.",";
            $userid=UserDoctor::findOne(['hospitalid'=>$v->source])->userid;
            echo "doctorid=".$userid.",";
            if($userid) {
                $doctorParent = DoctorParent::findOne(['doctorid'=>$userid,'parentid'=>$v->userid]);
                if(!$doctorParent)
                {
                    $doctorP=new DoctorParent();
                    $doctorP->doctorid=$userid;
                    $doctorP->parentid=$v->userid;
                    $doctorP->level=-1;
                    if($doctorP->save()) {
                        echo "成功\n";
                    }else{
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

        $file_list=glob("data/*.csv");
        foreach($file_list as $fk=>$fv)
        {
            preg_match("#\d+#",$fv,$m);
            $hospitalid=$m[0];
            if($hospitalid)
            {
                $f=fopen($fv,'r');
                $i=0;
                while(($line=fgets($f))!==false) {
                    echo $i."===";
                    $i++;
                    $row=explode(",",trim($line));
                    if(strlen($row[31])<11 && strlen($row[35])<11 && strlen($row[12])<11)
                    {
                        echo "--31-".$row['31'];
                        echo "--35-".$row['35'];
                        echo "--12-".$row['12'];

                        echo "无手机号\n";
                        continue;
                    }

                    $phone=strlen($row[31])==11?$row[31]:strlen($row[12])==11?$row[12]:$row[35];
                    if(!$phone || strlen($phone)!=11) {
                        echo "手机号不合法\n";
                        continue;
                    }
                    $user = User::findOne(['phone' => $phone]);
                    $user = $user ? $user : new User();
                    $user->phone = $phone;
                    $user->source = 2;
                    $user->type = 1;
                    $user->createtime=time();
                    echo $user->id."====";
                    if($user->save())
                    {
                        $login=UserLogin::findOne(['userid'=>$user->id]);
                        $login = $login ? $login : new UserLogin();
                        $login->userid=$user->id;
                        $login->password= md5(md5($user->phone."2QH@6%3(87"));
                        $login->save();
                        $userparent = UserParent::findOne(['userid' => $user->id]);
                        $userparent = $userparent ? $userparent : new UserParent();
                        echo $row[8]."====";

                        $userparent->userid = $user->id;
                        $userparent->mother = $row[8];
                        $userparent->mother_phone = intval($row[31]);
                        $userparent->father_phone = intval($row[35]);

                        $userparent->father = $row[10];
                        $userparent->mother_id = $row[9];
                        $userparent->father_birthday = strtotime($row[32]);
                        $userparent->address = $row[36];
                        $userparent->source=$hospitalid;
                        $userparent->field1=$row[1];
                        $userparent->field34=$row[34];
                        $userparent->field33=$row[33];
                        $userparent->field30=$row[30];
                        $userparent->field29=$row[29];
                        $userparent->field28=$row[28];
                        $userparent->field12=intval($row[12]);
                        $userparent->field11=$row[11];
                        if ($userparent->save()) {
                            $child=ChildInfo::findOne(['name'=>$row[3],'userid'=>$user->id]);
                            $child = $child ? $child : new ChildInfo();
                            $child->userid = $user->id;
                            $child->name = $row[3];
                            echo $row[3]."====";

                            $child->gender = $row[4] == "男" ? 1 : 2;
                            $child->birthday = intval(strtotime($row[5]));
                            $child->createtime=time();
                            $child->source=$hospitalid;
                            $child->field54= $row[54];
                            $child->field53= $row[53];
                            $child->field52= $row[52];
                            $child->field51= $row[51];
                            $child->field50= $row[50];
                            $child->field49= $row[49];
                            $child->field48= $row[48];
                            $child->field47= $row[47];
                            $child->field46= $row[46];
                            $child->field45= $row[45];
                            $child->field44= $row[44];
                            $child->field43= $row[43];
                            $child->field42= $row[42];
                            $child->field41= $row[41];
                            $child->field40= $row[40];
                            $child->field39= $row[39];
                            $child->field38= $row[38];
                            $child->field37= $row[37];
                            $child->field27= $row[27];
                            $child->field26= $row[26];
                            $child->field25= $row[25];
                            $child->field24= $row[24];
                            $child->field23= $row[23];
                            $child->field22= $row[22];
                            $child->field21= $row[21];
                            $child->field20= $row[20];
                            $child->field19= $row[19];
                            $child->field18= $row[18];
                            $child->field17= $row[17];
                            $child->field16= $row[16];
                            $child->field15= $row[15];
                            $child->field14= $row[14];
                            $child->field13= $row[13];
                            $child->field7= $row[7];
                            $child->field6= $row[6];
                            $child->field0= $row[0];
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
    public function actionDelete()
    {
        $user=User::findAll(['source'=>2]);
        foreach ($user as $k=>$v)
        {
           // $v->delete();
        }
    }
    public function actionLogin()
    {
        $user=User::findAll(['source'=>2]);
        foreach ($user as $k=>$v)
        {
            $login=UserLogin::findOne(['userid'=>$v->id]);
            if(!$login)
            {
                $login=new UserLogin();
                $login->userid=$v->id;
                $login->password= md5(md5($v->phone."2QH@6%3(87"));
                $login->save();
                echo $v->id."\n";
            }
        }

    }
    public function actionTest()
    {
        ChatRecord::updateAll(['read'=>1],['touserid'=>18486,'userid'=>4146]);
    }

}