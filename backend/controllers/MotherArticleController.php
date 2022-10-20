<?php

namespace backend\controllers;

use Yii;
use common\models\MotherArticle;
use backend\models\MotherArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\components\UploadForm;
use yii\helpers\Html;

/**
 * MotherArticleController implements the CRUD actions for MotherArticle model.
 */
class MotherArticleController extends Controller
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
     * Lists all MotherArticle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MotherArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MotherArticle model.
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
     * Creates a new MotherArticle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MotherArticle();

        if ($model->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post())) {

            if($model->save())
            {
                $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($model,'qcode'));
                if($imagesFile) {
                    $upload= new UploadForm();
                    $upload->imageFiles = $imagesFile;
                    $image = $upload->upload();
                    $model->qcode = $image[0];
                }

                if($model->save()) {
                    \Yii::$app->getSession()->setFlash('success','成功');

                    return $this->redirect(['view', 'id' => $model->id]);
                }

            }
            \Yii::$app->getSession()->setFlash('error', implode(',',$model->firstErrors).implode(',',$model->firstErrors));
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MotherArticle model.
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
     * Deletes an existing MotherArticle model.
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
     * Finds the MotherArticle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MotherArticle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MotherArticle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
