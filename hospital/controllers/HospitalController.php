<?php

namespace hospital\controllers;

use Yii;
use common\models\Hospital;
use hospital\models\user\HospitalSearchModel;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HospitalController implements the CRUD actions for Hospital model.
 */
class HospitalController extends BaseController
{


    /**
     * Lists all Hospital models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HospitalSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'chile_type' =>$searchModel->chile_type
        ]);
    }

    /**
     * Lists all Hospital models.
     * @return mixed
     */
    public function actionGet()
    {
        $searchModel = new HospitalSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        echo Html::tag('option',Html::encode("请选择"),array('value'=>0));

        foreach($dataProvider->query->all() as $k=>$v)
        {
            echo Html::tag('option',Html::encode($v->name),array('value'=>$v->id));
        }
    }

    /**
     * Displays a single Hospital model.
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
     * Creates a new Hospital model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hospital();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Hospital model.
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
     * Deletes an existing Hospital model.
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
     * Finds the Hospital model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hospital the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hospital::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
