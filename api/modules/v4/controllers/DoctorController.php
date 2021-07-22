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
use common\models\UserDoctor;

class DoctorController extends \api\modules\v3\controllers\DoctorController
{
    public function actionEditParent(){
        $post = \Yii::$app->request->post();
        


    }

}