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
use common\models\Article;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Doctors;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use databackend\models\User;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use yii\data\Pagination;

class AppointController extends Controller
{
    public function actionDoctors($search=''){
        $query=UserDoctor::find();
        if($search){
            $query->andFilterWhere(['like','name',$search]);
        }
        $doctors=$query->orderBy('appoint desc')->all();

        $docs=[];
        $weekday=[1=>"一",2=>"二",3=>"三",4=>"四",5=>"五",6=>"六",7=>"日"];

        $doctorParent=DoctorParent::findOne(['parentid'=>$this->userid]);
        if($doctorParent)
        {
            $doctorid=$doctorParent->doctorid;
        }

        foreach($doctors as $k=>$v){
            $rs=$v->toArray();
            $uda=UserDoctorAppoint::findOne([['doctorid'=>$v->userid]]);
            if($uda->weeks){
                $weeks=str_split((string)$uda->weeks);
                $w=[];
                foreach($weeks as $wk=>$wv){
                    $w[]=$weekday[$wv];
                }
                $weeks="门诊时间 每周".implode('，',$w);
            }else{
                $weeks="";
            }
            $rs['name']=Hospital::findOne($v->hospitalid)->name;
            $rs['appoint_time']=$weeks;
            $docs[]=$rs;
            if($doctorid==$v->userid){
                $doc=$rs;
            }
        }




        return ['doctors'=>$docs,'doc'=>$doc];
    }

    public function actionForm($id){
        $childs=ChildInfo::findAll(['userid'=>$this->userid]);


        //doctor
        $appoint=UserDoctorAppoint::findOne(['doctorid'=>$id]);

        $phone=$this->userLogin->phone;
        $phone=$phone?$phone:$this->user->phone;
        return ['childs'=>$childs,'appoint'=>$appoint,'phone'=>$phone];
    }

    public function actionSave(){
        $post=\Yii::$app->request->post();

        $appoint=Appoint::findOne(['childid'=>$post['childid'],'type'=>$post['type'],'state'=>1]);
        if($appoint){
            return new Code(20020,'您有未完成的预约');
        }else {
            $model = new Appoint();
            $post['appoint_date'] = strtotime($post['appoint_date']);
            $post['state'] = 1;
            $post['userid'] = $this->userid;
            $post['loginid']=$this->userLogin->id;
            $model->load(["Appoint" => $post]);
            if ($model->save()) {
                //var_dump($doctor->name);
                $userLogin=$this->userLogin;
                if($userLogin->openid) {
                    $doctor=UserDoctor::findOne(['userid'=>$model->doctorid]);
                    if($doctor){
                        $hospital=Hospital::findOne($doctor->hospitalid);
                    }
                    $child=ChildInfo::findOne($model->childid);

                    $data = [
                        'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                        'keyword2' => ARRAY('value' => $hospital->name),
                        'keyword3' => ARRAY('value' => date('Y-m-d',$model->appoint_date)." ".Appoint::$timeText[$model->appoint_time]),
                        'keyword4' => ARRAY('value' => $child?$child->name:''),
                        'keyword5' => ARRAY('value' => $model->phone),
                        'keyword6' => ARRAY('value' => "预约成功"),
                        'keyword7' => ARRAY('value' => $model->createtime),
                        'keyword8' => ARRAY('value' => Appoint::$typeInfoText[$model->type]),
                    ];
                    $rs = WechatSendTmp::sendX($data,$userLogin->xopenid, 'Ejdm_Ih_W0Dyi6XrEV8Afrsg6HILZh0w8zg2uF0aIS0', '/pages/appoint/view?id='.$model->id,$post['formid']);
                }

                return ['id' => $model->id];
            } else {
                return new Code(20010, implode(':', $model->firstErrors));
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
        $row['time']=date('Y.m.d')."  ".Appoint::$timeText[$appoint->appoint_time];
        $row['child_name']=ChildInfo::findOne($appoint->childid)->name;

        return $row;
    }

    public function actionMy($state=1){
        $appoints=Appoint::findAll(['userid'=>$this->userid,'state'=>$state]);

        foreach($appoints as $k=>$v){
            $row=$v->toArray();
            $doctor=UserDoctor::findOne(['userid'=>$v->doctorid]);
            if($doctor){
                $hospital=Hospital::findOne($doctor->hospitalid);
            }
            $row['hospital']=$hospital->name;
            $row['type']=Appoint::$typeText[$v->type];
            $row['time']=date('Y.m.d')."  ".Appoint::$timeText[$v->appoint_time];
            $row['stateText']=Appoint::$stateText[$v->state];
            $row['child_name']=ChildInfo::findOne($v->childid)->name;
            $list[]=$row;
        }
        return $list;
    }
    public function actionDelete($id,$formid){

        $model=Appoint::findOne(['id'=>$id,'userid'=>$this->userid]);
        if(!$model->delete()){
            return new Code(20010,'取消失败！');
        }else{
            $userLogin=$this->userLogin;
            if($userLogin->openid) {
                $doctor=UserDoctor::findOne(['userid'=>$model->doctorid]);
                if($doctor){
                    $hospital=Hospital::findOne($doctor->hospitalid);
                }
                $child=ChildInfo::findOne($model->childid);

                $data = [
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => ARRAY('value' => date('Y-m-d',$model->appoint_date)." ".Appoint::$timeText[$model->appoint_time]),
                    'keyword3' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                    'keyword4' => ARRAY('value' => "已取消"),

                ];
                $rs = WechatSendTmp::sendX($data,$userLogin->xopenid, 'sG19zJw7LhBT-SrZYNJbuH1TTYtQFKfVEviXKf1ERFI','',$formid);
            }
        }
    }
    public function actionQrCode($id){
        QrCode::png('appoint:'.$id,false,Enum::QR_ECLEVEL_H,10);exit;
    }


}