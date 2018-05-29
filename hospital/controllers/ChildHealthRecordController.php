<?php

namespace hospital\controllers;

use Yii;
use common\models\ChildHealthRecord;
use hospital\models\ChildHealthRecordSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChildHealthRecordController implements the CRUD actions for ChildHealthRecord model.
 */
class ChildHealthRecordController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::className(), 'actions' => ['delete' => ['POST'],],],];
    }

    /**
     * Lists all ChildHealthRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChildHealthRecordSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);
    }

    /**
     * Displays a single ChildHealthRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new ChildHealthRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChildHealthRecord();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing ChildHealthRecord model.
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
            return $this->render('update', ['model' => $model,]);
        }
    }

    /**
     * Deletes an existing ChildHealthRecord model.
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
     * Finds the ChildHealthRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChildHealthRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChildHealthRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
