<?php

namespace backend\controllers;

use Yii;
use common\models\ChildInfo;
use common\models\ChildInfoSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChildInfoController implements the CRUD actions for ChildInfo model.
 */
class ChildInfoController extends BaseController
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
     * Lists all ChildInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new \backend\models\ChildInfoSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChildInfo model.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionView($id, $name)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $name),
        ]);
    }

    /**
     * Creates a new ChildInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChildInfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'name' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ChildInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionUpdate($id, $name)
    {
        $model = $this->findModel($id, $name);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'name' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ChildInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionDelete($id, $name)
    {
        $this->findModel($id, $name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChildInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $name
     * @return ChildInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $name)
    {
        if (($model = ChildInfo::findOne(['id' => $id, 'name' => $name])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
