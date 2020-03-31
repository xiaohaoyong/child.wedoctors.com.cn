<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/11/22
 * Time: 下午3:24
 */

namespace console\controllers;


use common\models\Appoint;
use yii\base\Controller;

class AppointController extends Controller
{
    public function actionOverdue(){

        $day=strtotime(date('Y-m-d'));
        Appoint::updateAll(['state' => 4], 'state!=3 and appoint_date <'.$day);

    }
}