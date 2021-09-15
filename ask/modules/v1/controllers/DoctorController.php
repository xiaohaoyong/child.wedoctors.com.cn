<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/19
 * Time: 下午1:31
 */

namespace ask\modules\v1\controllers;


use ask\controllers\Controller;
use common\models\FreeQuota;

class DoctorController extends Controller
{
    public function actionItem(){
        $quota=FreeQuota::Count();



        return ['quota'=>$quota];
    }

}