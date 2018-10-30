<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\controllers;

use common\models\Appoint;
use common\models\Article;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Doctors;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
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
        return ['childs'=>$childs,'appoint'=>$appoint];
    }
}