<?php

namespace databackend\controllers;

use common\models\DataUserPassword;
use Yii;
use common\models\DataUser;
use common\models\DataUserSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DataUserController implements the CRUD actions for DataUser model.
 */
class DataUserController extends BaseController
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
     * Lists all DataUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataUserSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataUser model.
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
     * Creates a new DataUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataUser();

        $model->load(Yii::$app->request->post());
        $model->password=md5(md5("data.wedoctors").$model->password);

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DataUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $id=Yii::$app->user->id;
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());

        if (Yii::$app->request->isPost) {
            $model->setScenario('update');
            if($model->validate()){
                $model->setScenario(Model::SCENARIO_DEFAULT);
                $model->password=User::setPassword($model->password1);
                if($model->save()){
                    \Yii::$app->getSession()->setFlash('success',"密码修改成功");

                }else{
                    \Yii::$app->getSession()->setFlash('error', implode(',',$model->firstErrors));
                }
            }else{
                \Yii::$app->getSession()->setFlash('error', implode(',',$model->firstErrors));
            }
            return $this->redirect(['update', 'id' => $model->id]);

        } else {
            $model->password='';
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DataUser model.
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
     * Finds the DataUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DataUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataUserPassword::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
