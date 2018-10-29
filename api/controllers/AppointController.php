<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace api\controllers;

use common\models\Article;
use common\models\ArticleLike;
use common\models\ArticleLog;
use common\models\ArticleUser;
use common\models\Carousel;
use common\models\Doctors;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use yii\data\Pagination;

class AppointController extends Controller
{
    public function actionDoctors(){
        $doctors=UserDoctor::find()->orderBy('appoint desc')->all();
        $docs=[];
        $weekday=[1=>"一",2=>"二",3=>"三",4=>"四",5=>"五",6=>"六",7=>"日"];

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
        }
        return $docs;
    }
}