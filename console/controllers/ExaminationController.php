<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/5
 * Time: 上午11:34
 */

namespace console\controllers;


use common\helpers\WechatSendTmp;
use common\models\Examination;
use common\models\Notice;
use common\models\UserLogin;
use yii\console\Controller;

class ExaminationController extends Controller
{
    public function actionRemind(){
        $date=date('Y-m-d',strtotime('+1 day'));

        $ex=Examination::find()->andWhere(['!=','childid',0])->andFilterWhere(['field52'=>$date])->all();
       // echo $ex->createCommand()->getRawSql();exit;
        foreach ($ex as $k=>$v){

            $userid=$v->child->userid;
            echo $userid;
            echo "========";
            if($userid){
                Notice::setList($userid, 1, ['title' => '宝宝近期有健康体检，请做好准备', 'ftitle' => $date, 'id' => '/user/examination/index?id='.$v->childid,],"id=".$v->childid);
                $login=UserLogin::findOne(['userid'=>$userid]);
                if($login->openid)
                {
                    $openid=$login->openid;
                    echo $openid;
                    $data = [
                        'first' => array('value' => "宝宝近期有健康体检，请做好准备。\n"),
                        'keyword1' => ARRAY('value' => '﻿社区健康体检', ),
                        'keyword2' => ARRAY('value' => '近期'),
                        'remark' => ARRAY('value' => "\n ﻿注意：本次提醒是按照社区医生针对宝宝上次体检的时间进行预估，请根据社区门诊日安排出行，如有偏差请于保健科联系（儿童体检时间表：出生后第42天、4月龄、6月龄、9月龄、12月龄、18月龄、2周岁、3周岁）", 'color' => '#221d95'),
                    ];

                    $miniprogram=[
                        "appid"=>\Yii::$app->params['wxXAppId'],
                        "pagepath"=>"/pages/user/examination/index?id=".$v->childid,
                    ];
                    WechatSendTmp::send($data, $openid, 'b1mjgyGxK-YzQgo3IaGARjC6rkRN3qu56iDjbD6hir4', '',$miniprogram);
                }else{
                    echo "null";
                }
            }
            echo "\n";
        }
    }

}