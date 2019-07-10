<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace docapi\modules\v1\controllers;


use common\models\Doctors;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserLogin;

class UserController extends \docapi\controllers\UserController
{
    public function actionIndex(){
        $doctor = Doctors::findOne(['userid' => $this->userid]);
        $userDoctor=UserDoctor::findOne(['hospitalid'=>$doctor->hospitalid]);
        $row=$userDoctor->toArray();
        $row['hospital']=Hospital::findOne(['id'=>$doctor->hospitalid])->name;
        return $row;
    }
    public function actionDoctor(){
        $userLogin = UserLogin::findOne(['userid' => $this->userid]);
        return $userLogin;
    }

}