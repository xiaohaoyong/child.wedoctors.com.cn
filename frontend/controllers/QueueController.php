<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/1/26
 * Time: 下午2:11
 */

namespace frontend\controllers;

use common\components\Code;
use common\models\Appoint;
use common\models\AppointCalling;
use common\models\AppointCallingList;
use common\models\HospitalAppoint;
use common\models\queuing\Queue;

class QueueController extends ApiController
{
    public function actionAdd($aid,$doctorid){


        $appoint = Appoint::findOne(['id'=>$aid,'doctorid'=>$doctorid]);
        if (!$appoint) {
            return new Code(20000,'未查询到您的预约信息');
        } elseif($appoint->state==2){
            return new Code(20000,'您的预约已完成，请勿再次排号');
        } elseif($appoint->state==3){
            return new Code(20000,'您的预约已取消');
        } elseif($appoint->state==4){
            return new Code(20000,'您的预约已过期');
        }else{
            $appointCallingListModel = AppointCallingList::findOne(['aid' => $appoint->id]);
            //判断用户是否已经排队
            if ($appointCallingListModel) {
                if ($appointCallingListModel->state == 1) {
                    $queue = new Queue($appoint->doctorid, $appoint->type, $appoint->appoint_time);
                    $num2 = $queue->search($appointCallingListModel->id);
                    $text[] = "取号时间：" . date('Y年m月d日 H:i',$appointCallingListModel->createtime);
                    $text[] = "预约时间：" . date('Y年m月d日', $appoint->appoint_date)." ".Appoint::$timeText[$appoint->appoint_time];
                    $text[] = "号码：" . AppointCallingList::listName($appointCallingListModel->id, $appoint->doctorid, $appoint->type);
                    $text[] = "前方等待：" . $num2 . "位";
                    $txt = implode("\n", $text);
                    return $txt;
                } elseif ($appointCallingListModel->state == 3) {
                    return new Code(20000,'您的预约已完成，请勿再次排号');
                } elseif ($appointCallingListModel->state == 2) {
                    //过期重排
                    $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $appoint->doctorid, 'type' => $appoint->type]);
                    $timeType = Appoint::getTimeType($hospitalAppoint->interval, date('H:i'));
                    $appointCallingListModel->time = $timeType;
                    if ($appointCallingListModel->save()) {
                        $queue = new Queue($appoint->doctorid, $appoint->type, $appoint->appoint_time);
                        $queueNum = $queue->lpush($appointCallingListModel->id);
                        $text[] = "您的预约已过期，系统已为您重新排号：";
                        $text[] = "取号时间：" . date('Y年m月d日 H:i');
                        $text[] = "预约时间：" . date('Y年m月d日', $appoint->appoint_date)." ".Appoint::$timeText[$appoint->appoint_time];
                        $text[] = "号码：" . AppointCallingList::listName($appointCallingListModel->id, $appoint->doctorid, $appoint->type);
                        $text[] = "前方等待：" . ($queueNum - 1) . "位";
                        $txt = implode("\n", $text);
                        return $txt;
                    }
                }
            } else {
                //排队
                $appointCallingListModel = new AppointCallingList();
                $appointCallingListModel->aid = $appoint->id;
                //$appointCallingListModel->openid = $openid;
                $appointCallingListModel->doctorid = $appoint->doctorid;
                $appointCallingListModel->time = $appoint->appoint_time;
                $appointCallingListModel->type = $appoint->type;
                if ($appointCallingListModel->save()) {
                    $queue = new Queue($appoint->doctorid, $appoint->type, $appoint->appoint_time);
                    $queueNum = $queue->lpush($appointCallingListModel->id);

                    $text[] = "取号时间：" . date('Y年m月d日 H:i');
                    $text[] = "预约时间：" . date('Y年m月d日', $appoint->appoint_date)." ".Appoint::$timeText[$appoint->appoint_time];
                    $text[] = "号码：" . AppointCallingList::listName($appointCallingListModel->id, $appoint->doctorid, $appoint->type);
                    $text[] = "前方等待：" . ($queueNum - 1) . "位";
                    $txt = implode("\n", $text);
                    return $txt;
                }
            }
        }
    }

}