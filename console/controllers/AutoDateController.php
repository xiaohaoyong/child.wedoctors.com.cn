<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/11/23
 * Time: 下午7:42
 */

namespace console\controllers;


use common\components\Log;
use common\models\Access;
use common\models\Appoint;
use common\models\Area;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\TmpLog;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use console\models\Pregnancy;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\console\Controller;

class AutoDateController extends Controller
{
    public function actionRenew()
    {

        $auto = Autograph::find()->where(['<','endtime',date('Ymd')])->all();
        foreach ($auto as $k => $v) {
            $v->endtime=date('Ymd',strtotime('+1 year',strtotime($v->endtime)));
            $v->starttime=date('Ymd',strtotime('-1 year',strtotime($v->endtime)));
            $v->save();
            echo count($auto)."-".$v->endtime;
            echo "\n";
        }
    }
}
