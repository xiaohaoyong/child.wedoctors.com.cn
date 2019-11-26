<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/20
 * Time: 下午2:46
 */

namespace api\modules\v2\controllers;

use api\controllers\Controller;
use common\components\Code;
use common\models\ClockIn;
use common\models\Points;

class ClockInController extends Controller
{
    public function actionSet(){
        $clockIn=new ClockIn();
        $return= $clockIn->action($this->userid);
        if($return==20010){
            return new Code(20010,'今日已签到');
        }
    }
    public function actionIndex(){

        $clockIn=ClockIn::find()->where(['userid'=>$this->userid])
            ->andWhere(['datetime'=>date('Ymd')])
            ->one();
        if(!$clockIn) {
            $clockIn = ClockIn::find()->where(['userid' => $this->userid])
                ->andWhere(['datetime' => date('Ymd', strtotime('-1 day'))])
                ->one();
        }else{
            $k=1;
        }
        if($clockIn){
            $day=$clockIn->day;
        }else{
            $day=0;
        }
        $day=$day>=7?$day%7:$day;

        for($i=$day;$i>0;$i--){
            $i=$k && $i==1?$i=0:$i;
            $datetime=date('n.j',strtotime("-$i day"));
            $rs['day']=$datetime;
            $rs['s']=1;
            $row[]=$rs;
        }

        $d=7-$day;
        if($k){
            $d=$d+1;
            $j=1;
        }else{
            $j=0;
        }
        for($j;$j<$d;$j++){
            $datetime=date('n.j',strtotime("+$j day"));
            $rs['day']=$datetime;
            $rs['s']=0;
            $row[]=$rs;
        }

        $start=strtotime(date('Y-m-d 00:00:00'));
        $point=Points::find()
            ->select('source')
            ->where(['userid'=>$this->userid])
            ->andWhere(['>','createtime',$start])
            ->andWhere(['>','point',0])
            ->groupBy('source')
            ->column();
        $total=Points::find()
            ->where(['userid'=>$this->userid])
            ->sum('point');




        return ['row'=>$row,'point'=>implode(',',$point),'total'=>$total?$total:0,'clockIn'=>$clockIn];
    }

    public function actionUser(){

        $clockIn=ClockIn::find()->where(['userid'=>$this->userid])
            ->andWhere(['datetime'=>date('Ymd')])
            ->one();
        if($clockIn){
            return 1;
        }else{
            return 0;
        }
    }

}