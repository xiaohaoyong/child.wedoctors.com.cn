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
use hospital\models\AppointCallingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AppointCallingController implements the CRUD actions for AppointCalling model.
 */
class AppointCallingController extends Controller
{
    public function actionList($doctorid=590848,$type){

        error_reporting(E_ALL ^ E_NOTICE);
        \Yii::$app->response->format=Response::FORMAT_JSON;

        $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        $timeType = Appoint::getTimeType($hospitalAppoint->interval, date('H:i'));
        //当前时间段排队
        $queue = new Queue($doctorid, $type, $timeType,$type?false:true);
        $list[] = $queue->lrange();

        //其他时间段排队
        foreach(Appoint::$timeText as $k=>$v){
            if($k!=$timeType) {
                $queue = new Queue($doctorid, $type, $k,$type?false:true);
                $list[] = $queue->lrange();
            }
        }
        //临时号排队
        $queue = new Queue($doctorid, $type, 0,$type?false:true);
        $list[] = $queue->lrange();


        $i=0;
        foreach($list as $k=>$v) {
            if ($v) {
                foreach ($v as $vk => $vv) {
                    $i++;
                    if ($i > 4) {
                        $i = 0;
                        break;
                    }
                    $appointCallingListModel = \common\models\AppointCallingList::findOne($vv);
                    if (!$appointCallingListModel) continue;
                    if (!$appointCallingListModel->aid) {
                        $name = "临时";
                    } else {
                        $appoint = \common\models\Appoint::findOne($appointCallingListModel->aid);

                        $name = $appoint->name();
                    }
                    if ($appointCallingListModel->acid) {
                        $appointCalling = \common\models\AppointCalling::findOne($appointCallingListModel->acid);
                        $zname = $appointCalling->name;
                    } else {
                        $zname = "待定";
                    }
                    $num = $appointCallingListModel->time . \common\models\AppointCallingList::listName($appointCallingListModel->id, $appointCallingListModel->doctorid, $appointCallingListModel->type, $appointCallingListModel->time);

                    $rs['num']=$num;
                    $rs['name']=$name.$vv;
                    $rs['zname']=$zname;
                    $rs['times']=$appointCallingListModel->time?\common\models\Appoint::$timeText[$appointCallingListModel->time]:'临时';
                    $rs['is_read']=$appointCallingListModel->acid&&$appointCallingListModel->calling?1:0;
                    $lista[]=$rs;
                }
            }
        }
        return ['lists'=>$lista];

    }
}
