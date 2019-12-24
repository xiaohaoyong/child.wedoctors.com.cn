<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/5
 * Time: 上午11:34
 */

namespace console\controllers;


use common\components\Log;
use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Examination;
use common\models\ExaNoticeSetup;
use common\models\Notice;
use common\models\UserDoctor;
use common\models\UserLogin;
use yii\console\Controller;

class ExaminationController extends Controller
{
    public function actionRemind()
    {
        $date = date('Y-m-d', strtotime('+1 day'));

        $ex = Examination::find()->andWhere(['!=', 'childid', 0])->andFilterWhere(['field52' => $date])->all();
        // echo $ex->createCommand()->getRawSql();exit;
        foreach ($ex as $k => $v) {

            $userid = $v->child->userid;
            echo $userid;
            echo "========";
            if ($userid) {
                Notice::setList($userid, 1, ['title' => '宝宝近期有健康体检，请做好准备', 'ftitle' => $date, 'id' => '/user/examination/index?id=' . $v->childid,], "id=" . $v->childid);
                $login = UserLogin::findOne(['userid' => $userid]);
                if ($login->openid) {
                    $openid = $login->openid;
                    echo $openid;
                    $data = [
                        'first' => array('value' => "宝宝近期有健康体检，请做好准备。\n"),
                        'keyword1' => ARRAY('value' => '﻿社区健康体检',),
                        'keyword2' => ARRAY('value' => '近期'),
                        'remark' => ARRAY('value' => "\n ﻿注意：本次提醒是按照社区医生针对宝宝上次体检的时间进行预估，请根据社区门诊日安排出行，如有偏差请于保健科联系（儿童体检时间表：出生后第42天、4月龄、6月龄、9月龄、12月龄、18月龄、2周岁、2岁6月龄、3周岁）", 'color' => '#221d95'),
                    ];

                    $miniprogram = [
                        "appid" => \Yii::$app->params['wxXAppId'],
                        "pagepath" => "/pages/user/examination/index?id=" . $v->childid,
                    ];
                    WechatSendTmp::send($data, $openid, 'b1mjgyGxK-YzQgo3IaGARjC6rkRN3qu56iDjbD6hir4', '', $miniprogram);
                } else {
                    echo "null";
                }
            }
            echo "\n";
        }
    }

    public function actionNotice()
    {
        $log=new Log('exa-notice');
        $exaList = ExaNoticeSetup::findAll(['level' => 1]);
        foreach ($exaList as $k => $v) {
            for ($i = 1; $i <= 8; $i++) {
                $field = "month" . $i;
                $month = $v->$field;
                if ($v->$field) {
                    $doctor = UserDoctor::findOne(['hospitalid' => $v->hospitalid]);
                    $date = date('Y-m-d', strtotime("-$month month +1 day"));
                    $childs = ChildInfo::find()
                        ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                        ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctor->userid])
                        ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                        ->andFilterWhere(['child_info.admin' => $v->hospitalid])
                        ->andFilterWhere(['`child_info`.birthday'=>strtotime($date)])
                        ->andFilterWhere(['`child_info`.`doctorid`' => $v->hospitalid])
                        ->all();
                    $log->addLog('前');
                    foreach($childs as $ck=>$cv){
                        //Notice::setList($cv->userid, 1, ['title' => '宝宝近期有健康体检，请做好准备', 'ftitle' => $date, 'id' => '/user/examination/index?id=' . $v->childid,], "id=" . $v->childid);
                        $login = UserLogin::find()->select('openid')->where(['userid'=>$cv->userid])->andWhere(["!=",'openid',''])->column();
                        if($login) {
                            $data = [
                                'first' => array('value' => "您好家长，您的宝宝可以进行定期常规体检了"),
                                'keyword1' => ARRAY('value' => '社区健康体检',),
                                'keyword2' => ARRAY('value' => '近期'),
                                'remark' => ARRAY('value' => "请您自今天起一个月内带孩子到本社区卫生服务中心保健科体检。体检时间查看预防保健科门诊日。注意：如有超期不再体检（儿童体检时间表：出生后第42天、3月龄、6月龄、9月龄、12月龄、18月龄、2周岁、2岁6月龄、3周岁）", 'color' => '#221d95'),
                            ];

                            $miniprogram = [
                                "appid" => \Yii::$app->params['wxXAppId'],
                                "pagepath" => "/pages/user/examination/index?id=" . $cv->id,
                            ];
                            foreach($login as $lk=>$lv){
                                $log->addLog($lv);
                                //WechatSendTmp::send($data, "o5ODa0451fMb_sJ1D1T4YhYXDOcg", 'b1mjgyGxK-YzQgo3IaGARjC6rkRN3qu56iDjbD6hir4', '', $miniprogram);
                            }
                        }
                    }
                    $log->addLog('后');
                    $endMonth=ExaNoticeSetup::endList($i);
                    $endDate = date('Y-m-d', strtotime("-$endMonth month +7 day"));
                    $endchilds = ChildInfo::find()
                        ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                        ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctor->userid])
                        ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                        ->andFilterWhere(['child_info.admin' => $v->hospitalid])
                        ->andFilterWhere(['`child_info`.birthday'=>strtotime($endDate)])
                        ->andFilterWhere(['`child_info`.`doctorid`' => $v->hospitalid])
                        ->all();
                    foreach($endchilds as $ck=>$cv){
                        //Notice::setList($cv->userid, 1, ['title' => '宝宝近期有健康体检，请做好准备', 'ftitle' => $date, 'id' => '/user/examination/index?id=' . $v->childid,], "id=" . $v->childid);
                        $login = UserLogin::find()->select('openid')->where(['userid'=>$cv->userid])->andWhere(["!=",'openid',''])->column();
                        if($login) {
                            $data = [
                                'first' => array('value' => "您好家长，您的宝宝如果还没有进行常规体检，请您在一周之内带孩子到保健科体检"),
                                'keyword1' => ARRAY('value' => '社区健康体检',),
                                'keyword2' => ARRAY('value' => '一周内'),
                                'remark' => ARRAY('value' => "体检时间查看预防保健科门诊日。如已体检，请忽略。注意：如有超期不再体检。（儿童体检时间表：出生后第42天、3月龄、6月龄、9月龄、12月龄、18月龄、2周岁、2岁6月龄、3周岁）", 'color' => '#221d95'),
                            ];

                            $miniprogram = [
                                "appid" => \Yii::$app->params['wxXAppId'],
                                "pagepath" => "/pages/user/examination/index?id=" . $cv->id,
                            ];
                            foreach($login as $lk=>$lv){
                                $log->addLog($lv);

//                                WechatSendTmp::send($data, "o5ODa0451fMb_sJ1D1T4YhYXDOcg", 'b1mjgyGxK-YzQgo3IaGARjC6rkRN3qu56iDjbD6hir4', '', $miniprogram);
//                                exit;
                            }
                        }
                    }

                }
            }
        }
    }

}