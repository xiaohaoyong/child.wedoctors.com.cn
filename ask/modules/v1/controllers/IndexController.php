<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/16
 * Time: 下午4:13
 */

namespace ask\modules\v1\controllers;


use ask\controllers\Controller;
use common\models\FreeQuota;

class IndexController extends Controller
{
    public function actionIndex(){
        $quota=FreeQuota::Count();
        $quota_count=FreeQuota::get_quota_tmp();
        return ['free_quota'=>['quota'=>$quota,'quota_count'=>$quota_count]];
    }

    public function actionGetQuota(){

        $quota=FreeQuota::get();
        if($quota){

        }
    }

}