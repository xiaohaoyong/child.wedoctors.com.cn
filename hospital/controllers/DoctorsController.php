<?php

namespace hospital\controllers;

use common\models\User;
use Yii;
use common\models\Doctors;
use backend\models\DoctorsSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DoctorsController implements the CRUD actions for Doctors model.
 */
class DoctorsController extends Controller
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
     * Lists all Doctors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorsSearchModels();
        $params=Yii::$app->request->queryParams;
        $params['DoctorsSearchModels']['hospitalid']=Yii::$app->user->identity->hospitalid;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Doctors model.
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
     * Creates a new Doctors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Doctors();

        $model->province=11;
        $model->city=11;
        $model->county=Yii::$app->user->identity->county;
        $model->hospitalid=Yii::$app->user->identity->hospitalid;
        //var_dump(Yii::$app->request->post());exit;
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->userid]);
            }
        }
        if ($model->firstErrors) {
            \Yii::$app->getSession()->setFlash('error', implode(',', $model->firstErrors));
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Doctors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->type){
            $t=(string)decbin($model->type);
            $c=strlen($t);
            for($i=0;$i<$c;$i++){
                if((string)$t[$i]==1) {
                    $d[] = pow(10, $i);
                }
            }
            $model->type=$d;
        }
        if($user=User::findOne($model->userid)){
            $model->phone=$user->phone;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->userid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Doctors model.
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
     * Finds the Doctors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
