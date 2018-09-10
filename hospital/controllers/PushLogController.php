<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/9/7
 * Time: 上午10:40
 */

namespace hospital\controllers;


use hospital\models\PushLogSearchModel;

class PushLogController extends BaseController
{
    public function actionIndex(){
        $searchModel = new PushLogSearchModel();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}