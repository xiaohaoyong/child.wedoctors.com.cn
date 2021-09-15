<?php

namespace backend\controllers;

use Yii;
use common\models\QuestionNaire;
    use backend\models\QuestionNaireSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
* QuestionNaireController implements the CRUD actions for QuestionNaire model.
*/
class QuestionNaireController extends Controller
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
* Lists all QuestionNaire models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel = new QuestionNaireSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    ]);
}

/**
* Displays a single QuestionNaire model.
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
* Creates a new QuestionNaire model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new QuestionNaire();

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', 'id' => $model->id]);
} else {
return $this->render('create', [
'model' => $model,
]);
}
}

/**
* Updates an existing QuestionNaire model.
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
* Deletes an existing QuestionNaire model.
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
* Finds the QuestionNaire model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return QuestionNaire the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel($id)
{
if (($model = QuestionNaire::findOne($id)) !== null) {
return $model;
} else {
throw new NotFoundHttpException('The requested page does not exist.');
}
}
}
