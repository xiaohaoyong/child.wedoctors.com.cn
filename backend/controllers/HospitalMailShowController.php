<?php

namespace backend\controllers;

use Yii;
use common\models\HospitalMailShow;
    use backend\models\HospitalMailShowSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
* HospitalMailShowController implements the CRUD actions for HospitalMailShow model.
*/
class HospitalMailShowController extends BaseController
{
/**
* @inheritdoc
*/
public function behaviors()
{
return [
'verbs' => [
'class' => VerbFilter::className(),
'actions' => [
'delete' => ['POST'],
],
],
];
}

/**
* Lists all HospitalMailShow models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel = new HospitalMailShowSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    ]);
}

/**
* Displays a single HospitalMailShow model.
* @param integer $id
* @return mixed
*/
public function actionView($id)
{
return $this->render('view', [
'model' => $this->findModel($id),
]);
}

/**
* Creates a new HospitalMailShow model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new HospitalMailShow();

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', 'id' => $model->id]);
} else {
return $this->render('create', [
'model' => $model,
]);
}
}

/**
* Updates an existing HospitalMailShow model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param integer $id
* @return mixed
*/
public function actionUpdate($id)
{
$model = $this->findModel($id);

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', 'id' => $model->id]);
} else {
return $this->render('update', [
'model' => $model,
]);
}
}

/**
* Deletes an existing HospitalMailShow model.
* If deletion is successful, the browser will be redirected to the 'index' page.
* @param integer $id
* @return mixed
*/
public function actionDelete($id)
{
$this->findModel($id)->delete();

return $this->redirect(['index']);
}

/**
* Finds the HospitalMailShow model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return HospitalMailShow the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel($id)
{
if (($model = HospitalMailShow::findOne($id)) !== null) {
return $model;
} else {
throw new NotFoundHttpException('The requested page does not exist.');
}
}
}
