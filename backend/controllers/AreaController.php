<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/5/2
 * Time: 上午11:32
 */

namespace backend\controllers;


use common\models\Area;
use yii\helpers\Html;


class AreaController extends BaseController
{
    public function actionGet($id)
    {
        if(\Yii::$app->request->get('type')=='county'){
            $area = Area::$county[$id];
        }else{
            $area = Area::$city[$id];

        }
        echo Html::tag('option',Html::encode("请选择"),array('value'=>0));

        foreach($area as $k=>$v)
        {
            echo Html::tag('option',Html::encode($v),array('value'=>$k));
        }
    }

}