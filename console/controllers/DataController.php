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
use common\models\ArticleComment;
use common\models\ArticleUser;
use common\models\BabyTool;
use common\models\BabyToolTag;
use common\models\ChatRecord;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Examination;
use common\models\Hospital;
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
        if (!$dp && $dp1) {
            $dp = new DoctorParent();
            $dp->doctorid = $dp1->doctorid;
            $dp->level = 1;
            $dp->createtime = $dp1->createtime;
            $dp->parentid = $userid;
            $dp->save();
            echo "dp update==";
        } else {
            if (DoctorParent::deleteAll('parentid =' . $buserid)) {
                echo "dp delete==";
            }
        }
    }

    public function articleUser($childid, $userid, $bchildid)
    {
        //修改宣教记录所属儿童
        $articleUser = ArticleUser::findAll(['childid' => $bchildid]);
        if ($articleUser) {
            foreach ($articleUser as $av) {
                echo "artid1:" . $av->id . "==";
                $au = ArticleUser::find()->andWhere(['childid' => $childid])
                    ->andFilterWhere(['artid' => $av->artid])->one();
                echo "artid2:" . $au->id . "==";
                if (!$au) {
                    echo "update==";
                    $av->touserid = $userid;
                    $av->childid = $childid;
                    $av->save();
                } else {
                    echo "delete==";
                    $av->delete();
                }
            }
        } else {
            echo "article:无";
        }
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
                ->andWhere(['<','source','39'])
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
                        $user=User::findOne($cv->userid);
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
//        $childs = ChildInfo::find()->andFilterWhere(['doctorid' => 110565])->all();
//        foreach ($childs as $k => $v) {
//            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid, 'level' => 1]);
//            $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
//
//            $v->doctorid = $doctor->hospitalid;
//            $v->save();
//            echo $v->userid;
//            echo "\n";
//
//        }
//        exit;
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
        $rs=[];
        $file=fopen("openid2.log",'r');
        while(($line=fgets($file))!==false){
            $row=explode(',',trim($line));
            $openid=$row[0];
            $doctor=$row[1];
            $rs[$openid]=$doctor;
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




        foreach($rs as $k=>$v){

            $data = [
                'first' => array('value' => "您好，为确保享受儿童中医健康指导服务,请尽快完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => $v),
                'remark' => ARRAY('value' => "\n 点击完善宝宝信息", 'color' => '#221d95'),
            ];
            $rs = WechatSendTmp::send($data, $k,'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '',['appid'=>\Yii::$app->params['wxXAppId'],'pagepath'=>'pages/index/index',]);
            echo $k."\n";
        }
exit;

        $weOpenid=WeOpenid::find()->andWhere(['level'=>0])->all();
        foreach($weOpenid as $k=>$v){
            if($v->openid){
                $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
                $hospital=Hospital::findOne($doctor->hospitalid);
                echo $v->openid.",".$hospital->name."\n";
            }
        }

        $doctorParent= DoctorParent::findAll(['level'=>1]);
        foreach($doctorParent as $k=>$v){

            if(!ChildInfo::findOne(['userid'=>$v->parentid])) {
                $userLogin =UserLogin::findOne(['userid'=>$v->parentid]);
                $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
                $hospital=Hospital::findOne($doctor->hospitalid);
                if($userLogin && $userLogin->openid){
                    echo $userLogin->openid.",".$hospital->name;
                    echo "\n";
                }
            }
        }
        exit;




        foreach($weOpenid as $k=>$v) {
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
                $rs = WechatSendTmp::send($data,'o5ODa0wc1u3Ihu5WvCVqoACeQ-HA', '﻿wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', 'https://jinshuju.net/f/Sfodlu');
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
        //$userids=UserLogin::find()->where(['userid'=>'47388'])->all();

        //$userids = DoctorParent::find()->andFilterWhere(['parentid' => 77107])->all();


        $userids=WeOpenid::find()->andFilterWhere([">",'createtime','1529942400'])
            ->andFilterWhere(['level'=>0])
            ->andWhere(['!=','openid',''])
            ->all();
        $openids=[];
        if ($article) {
            foreach ($userids as $k => $v) {
                //$userLogin = UserLogin::findOne(['userid' => $v->userid]);
                //$userLogin=$v;
                if(in_array($v->openid,$openids))
                    $openids[]=$v->openid;
                if ($v->openid) {
                    $rs = WechatSendTmp::send($data, $v->openid,  \Yii::$app->params['zhidao'], '', $miniprogram);
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
            while (($line = fgets($f)) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $i++;
                echo $hospitalid . "=" . $i . "===";
                $row = explode(",", trim($line));

                $row[3] = substr($row[3], 0, strlen($row[3]) - 11);
                $ex = Examination::find()->andFilterWhere(['field1' => $row[0]])
                    ->andFilterWhere(['field2' => $row[1]])
                    ->andFilterWhere(['field3' => $row[2]])
                    ->andFilterWhere(['field4' => $row[3]])
                    ->andFilterWhere(['source' => $hospitalid])
                    ->andFilterWhere(['field19' => $row[18]])->one();
                // if($ex){ echo "jump\n";continue;}
                $isupdate = $ex ? 0 : 1;
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
                    'isupdate' => $isupdate,
                ];

                if (!$child) {
                    echo "--儿童不存在";
                    // $childData['childid'] = 0;
                } else {
                    echo "--儿童存在";
                    $childData['childid'] = $child->id;
                }


                $childData = array_filter($childData, function ($e) {
                    if ($e != '' || $e != null) return true;
                    return false;
                });
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
        $i=0;
        ini_set('memory_limit', '1024M');
        $ex = Examination::find()->andFilterWhere(['isupdate' => 1])->andFilterWhere(['>', 'childid', '0'])->andFilterWhere(['>','field4','2018-05-15'])->groupBy('childid')->all();


        foreach ($ex as $k => $v) {
            $child = ChildInfo::findOne(['id' => $v->childid]);
            if ($child) {
                //echo $child->id . "===$k" . "===";
                $login = $child->login;
                if ($login->openid && !in_array($login->openid,$logins)) {
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
                    //小程序首页通知
                    Notice::setList($login->userid, 1, ['title' => "宝宝近期的体检结果已更新", 'ftitle' => "点击可查看本体检报告的详细内容信息", 'id' => "/user/examination/index?id=" . $child->id,], "id=" . $child->id);

                    $i++;
                    echo $i;
                    echo "true\n";
                }
            }
        }
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
            }else{
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
            ->putInTube('push', ['artid' => 301, 'userids' => [49106]]);
        var_dump($return);
        exit;

        //ChatRecord::updateAll(['read'=>1],['touserid'=>18486,'userid'=>4146]);
    }

}