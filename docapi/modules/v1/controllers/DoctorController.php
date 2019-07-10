<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/19
 * Time: 下午1:31
 */

namespace docapi\modules\v1\controllers;

use backend\models\DoctorsSearchModels;
use common\models\Doctors;
use common\models\User;
use docapi\controllers\Controller;
use common\models\FreeQuota;

class DoctorController extends Controller
{
    public function actionItem(){
        $quota=FreeQuota::Count();



        return ['quota'=>$quota];
    }
    public function actionList(){

        $doctor = Doctors::findOne(['userid' => $this->userid]);

        $searchModel = new DoctorsSearchModels();
        $params=\Yii::$app->request->queryParams;
        $params['DoctorsSearchModels']['hospitalid']=$doctor->hospitalid;
        $dataProvider = $searchModel->search($params);

        foreach ($dataProvider->getModels() as $k => $v) {
            $row['name']=$v->name;
            $user=\common\models\User::findOne($v->userid);
            $row['phone']=$user->phone;
            $row['userid']=$user->id;

            $list[] = $row;
        }
        return $list;
    }
    public function actionView($id){

        $model = Doctors::findOne($id);
        $row=$model->toArray();
        if($user=User::findOne($model->userid)){
            $row['phone']=$user->phone;
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return true;
        }else{
            return $row;
        }

    }

}