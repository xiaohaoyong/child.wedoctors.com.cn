<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/7/9
 * Time: 4:23 PM
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\models\DoctorTeam;
use common\models\DoctorParent;
use common\models\TempUserid;
use common\models\UserDoctor;
use common\models\UserParent;
use common\models\ChildInfo;

use common\models\UserLogin;

class SystemController extends Controller
{
    public function actionDisable()
    {
        return ['visible'=>0];

        $userid = $this->userid;
        $tempUserid=TempUserid::findOne(['userid'=>$userid]);
        if($tempUserid){
            return ['visible'=>1];
        }else{
            return ['visible'=>0];
        }
    }

}