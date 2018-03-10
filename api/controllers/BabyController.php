<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/30
 * Time: 上午11:51
 */

namespace api\controllers;


use common\models\BabyTool;
use common\models\BabyToolTag;
use common\models\Vaccine;

class BabyController extends Controller
{
    public function actionTag(){

        $tags=BabyToolTag::find()->all();
        return $tags;
    }
    public function actionList($period){

        $list=BabyTool::findAll(['period'=>$period]);
        return $list;
    }
    public function actionVlist(){

        $list=Vaccine::find()->all();
        return $list;
    }
    public function actionVview($id){
        $view=Vaccine::findOne($id);
        return $view;
    }

}