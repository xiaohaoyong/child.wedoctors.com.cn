<?php

namespace backend\controllers;

use Yii;
use backend\models\AutoUser;
use backend\models\AutoUserSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AutoUserController implements the CRUD actions for AutoUser model.
 */
class AutoUserController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::className(), 'actions' => ['delete' => ['POST'],],],];
    }

    /**
     * Lists all AutoUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AutoUserSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);
    }

    /**
     * Displays a single AutoUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new AutoUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AutoUser();

        $model->load(Yii::$app->request->post());
        $model->password=md5(md5("data.wedoctors").$model->password);

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            var_dump($model->errors);exit;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AutoUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $password1=$model->password;
        $model->load(Yii::$app->request->post());

        if(Yii::$app->request->isPost && $model->password!=$password1)
        {
            $model->password=md5(md5("data.wedoctors").$model->password);
        }


        if (Yii::$app->request->isPost && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AutoUser model.
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
     * Finds the AutoUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AutoUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AutoUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
