<?php

namespace interfaces\controllers;

use common\helpers\baidu_tts\AipSpeech;
use common\models\Appoint;
use common\models\AppointCallingList;
use common\models\HospitalAppoint;
use common\models\queuing\Queue;
use common\models\UserDoctor;
use EasyWeChat\Factory;
use hospital\models\AppointSearchModels;
use Yii;
use common\models\AppointCalling;
use common\models\AppointQueue;

use hospital\models\AppointCallingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AppointCallingController implements the CRUD actions for AppointCalling model.
 */
class AppointCallingTestController extends Controller
{
    public function actionList($doctorid=590848,$type){

        error_reporting(E_ALL ^ E_NOTICE);
        \Yii::$app->response->format=Response::FORMAT_JSON; 

        $appointQueue = AppointQueue::find()->where(['doctorid'=>$doctorid,'type'=>$type,'appoint_date'=>date('Y-m-d'),'status'=>1])->orderBy('sort asc')->all();
        $is_read = 0;
        foreach($appointQueue as $k=>$v){
            $appointCalling=\common\models\AppointCalling::findOne($v->roomid);
            $rs['id']=$v->id;
            $rs['num']=$v->num;
            $rs['name']=$v->name;
            $rs['zname']=$v->roomid && $appointCalling?$appointCalling->name:'待定';
            $rs['times']=$v->time_type?\common\models\Appoint::$timeText[$v->time_type]:'临时';
            if($is_read){
                $rs['is_read'] = 0;
            }else{
                $rs['is_read'] = $v->is_read;
                if($v->is_read==1){
                    $is_read = 1;
                }
            }
            $lista[]=$rs;
        }
        return ['lists'=>$lista];

    }
    public function actionTtl($id){
        \Yii::$app->response->format=Response::FORMAT_JSON;
        $appointCallingList=AppointQueue::findOne($id);
        if($appointCallingList->is_read==1) {
            $appointCallingList->is_read = 0;
            $appointCallingList->save();
            return ['code'=>10000];
        }else{
            return ['code'=>20000];
        }
    }
}
