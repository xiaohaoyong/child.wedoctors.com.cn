<?php

namespace backend\controllers;


use OSS\OssClient;
use yii\web\Response;


use common\models\XlsxoutofInfo;
use backend\models\XlsxoutofInfoSearch;

class XlsxoutofController extends BaseController
{
    
    
    
    public function actionListInfo()
    {
        $searchModel = new XlsxoutofInfoSearch();
        
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        //print_r($dataProvider);
        
        return $this->render('list-info', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }
}