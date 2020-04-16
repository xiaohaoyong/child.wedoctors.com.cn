<?php

namespace hospital\controllers;

use hospital\models\user\ChildSearch;
use Yii;

/**
 * ChildInfoController implements the CRUD actions for ChildInfo model.
 */
class ChildController extends BaseController
{
    public function actionSigned(){

        $params=Yii::$app->request->queryParams;
        $searchModel = new ChildSearch();

        $dataProvider = $searchModel->search($params);

        return $this->render('signed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
