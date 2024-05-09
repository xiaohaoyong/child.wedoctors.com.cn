<?php

namespace backend\controllers;

use Yii;
use common\models\DoctorTeam;
    use backend\models\DoctorTeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
* DoctorTeamController implements the CRUD actions for DoctorTeam model.
*/
class DoctorTeamController extends Controller
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
* Lists all DoctorTeam models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel = new DoctorTeamSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    ]);
}

/**
* Displays a single DoctorTeam model.
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
* Creates a new DoctorTeam model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new DoctorTeam();

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', 'id' => $model->id]);
} else {
return $this->render('create', [
'model' => $model,
]);
}
}

/**
* Updates an existing DoctorTeam model.
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
* Deletes an existing DoctorTeam model.
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
* Finds the DoctorTeam model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $id
* @return DoctorTeam the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel($id)
{
if (($model = DoctorTeam::findOne($id)) !== null) {
return $model;
} else {
throw new NotFoundHttpException('The requested page does not exist.');
}
}

    /**
     * Lists all Hospital models.
     * @return mixed
     */
    public function actionGet()
    {
        $searchModel = new DoctorTeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        echo Html::tag('option',Html::encode("请选择"),array('value'=>0));



        foreach($dataProvider->query->all() as $k=>$v)
        {
            echo Html::tag('option',Html::encode($v->title),array('value'=>$v->id));
        }
    }

}
