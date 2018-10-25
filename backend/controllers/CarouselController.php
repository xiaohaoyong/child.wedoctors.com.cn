<?php

namespace backend\controllers;

use Yii;
use common\models\Carousel;
use backend\models\CarouselSearchModel;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CarouselController implements the CRUD actions for Carousel model.
 */
class CarouselController extends BaseController
{


    /**
     * Lists all Carousel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarouselSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);
    }

    /**
     * Displays a single Carousel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new Carousel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Carousel();

        if ($model->load(Yii::$app->request->post())) {

            $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($model,'img'));
            if($imagesFile) {
                $upload= new \common\components\UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
                $model->img = $image[0];
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing Carousel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($model,'img'));
            if($imagesFile) {
                $upload= new \common\components\UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
                $model->img = $image[0];
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model,]);
        }
    }

    /**
     * Deletes an existing Carousel model.
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
     * Finds the Carousel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Carousel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Carousel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
