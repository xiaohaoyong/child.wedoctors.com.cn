<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;

use api\models\ChildInfo;
use common\components\Code;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\UserDoctor;
use yii\base\Application;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;


class AppointController extends Controller
{
    //public $layout='@frontend/views/layouts/h5.php';
    public $hs = [
        '9RV7H6Dv' => [248035],
        'tU459foO' => [248033],
        'a94PW3iX' => [206262],
        'KPW01H7g' => [113890],
        'E1mUGz95' => [192821],
        'ER8GOz85'=>[257888]

    ];


    public function sign($h, $d, $s)
    {
        //echo md5($h.date('Ymd')."rh6FcKyWOUqF52hf");exit;
        if ($s == md5($h . date('Ymd') . "rh6FcKyWOUqF52hf")) {
            return true;
        }
        return false;
    }

    public function actionList($h, $d, $s)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $arr = [];

        if ($this->sign($h, $d, $s) && $this->hs[$h]) {

            $appoint = Appoint::find()->where(['in', 'doctorid', $this->hs[$h]])
                ->andWhere(['appoint_date' => strtotime(date('Ymd'))])
                ->all();
            foreach ($appoint as $k => $v) {
                $rs['pat_Id'] = "appoint:" . $v->id;
                $child = \common\models\ChildInfo::findOne(['id' => $v->childid]);
                $rs['pat_Name'] = $child->name;
                $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $child->birthday));
                if ($DiffDate[0]) {
                    $age = $DiffDate[0] . "岁";
                } elseif ($DiffDate[1]) {
                    $age = $DiffDate[1] . "月";
                } else {
                    $age = $DiffDate[2] . "天";
                }
                $rs['age'] = $age;
                $rs['yuyueRiqi'] = date('Y-m-d');


                $index = \common\models\Appoint::find()
                    ->andWhere(['appoint_date' => $v->appoint_date])
                    ->andWhere(['<', 'id', $v->id])
                    ->andWhere(['doctorid' => $v->doctorid])
                    ->andWhere(['appoint_time' => $v->appoint_time])
                    ->andWhere(['type' => $v->type])
                    ->count();
                $n = $index + 1;
                $rs['seq_No'] = $n;

                switch ($v->doctorid) {
                    case 248033:
                        $quyuName = 1;
                        break;
                    case 234820:
                        $quyuName = 2;
                        break;
                    default:
                        $quyuName = 0;
                        break;
                }

                $rs['dept_Name'] = $v->type == 1 ? 2 : 1;
                $rs['yuyueDate'] = Appoint::$timeText[$v->appoint_time];
                $rs['quyuName'] = $quyuName;
                $arr[] = $rs;
            }
        } else {
            $code = 20010;
            $msg = 'sign错误';
        }
        return ['code' => $code ? $code : 10000, 'msg' => $msg ? $msg : '成功', 'data' => $arr];
    }
    public function actionDone($h, $d, $s,$code=''){
        if ($this->sign($h, $d, $s) && $this->hs[$h]) {
            if($code){

            }
        }
        return ['code' => 20000, 'msg' => '请求失败'];
    }

}