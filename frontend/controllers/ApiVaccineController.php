<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/20
 * Time: 下午3:48
 */

namespace frontend\controllers;


use common\models\Vaccine;

class ApiVaccineController extends ApiController
{
    public function actionList(){
        $list=Vaccine::find()->select('id,name')->where(['>','source',0])->orderBy('source asc')->all();
        return $list;
    }
    public function actionView($id){
        $view=Vaccine::findOne($id);
        return $view;
    }
}