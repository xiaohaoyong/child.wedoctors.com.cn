<?php

namespace hospital\controllers;

use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\UserDoctor;
use common\models\UserParent;
use Yii;
use common\models\Autograph;
use hospital\models\AutographSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AutographController implements the CRUD actions for Autograph model.
 */
class AutographController extends BaseController
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
     * Lists all Autograph models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AutographSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Autograph model.
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
     * Displays a single Autograph model.
     * @param integer $id
     * @return mixed
     */
    public function actionDown($userid,$type=0)
    {
        if($type) {
            $userParent = UserParent::findOne(['userid' => $userid]);
        }

        $doctorParent=DoctorParent::findOne(['parentid'=>$userid]);
        $userDoctor=UserDoctor::findOne(['userid'=>$doctorParent->doctorid]);
        $child=ChildInfo::find()->where(['userid'=>$userid])->all();


        $view=Yii::$app->user->identity->hospitalid==110647?'down1':'down';
        return $this->renderPartial($view,[
            'userParent'=>$userParent,
            'userid'=>$userid,
            'userDoctor'=>$userDoctor,
            'child'=>$child,
        ]);
    }


    /**
     * Creates a new Autograph model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Autograph();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Autograph model.
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
     * Deletes an existing Autograph model.
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
     * Finds the Autograph model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Autograph the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Autograph::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
