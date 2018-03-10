<?php

namespace backend\controllers;

use backend\models\operate\AdminOperate;
use backend\models\operate\AdminOperateSearch;
use backend\models\rbac\AuthMenu;
use Yii;
use backend\models\operate\Operate;
use backend\models\operate\OperateSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperateController implements the CRUD actions for Operate model.
 */
class OperateController extends BaseController
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
     * Lists all Operate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminOperateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Operate model.
     * @param integer $id
     * @return mixed
     */
    //    public function actionView($id)
    //    {
    //        return $this->render('view', [
    //            'model' => $this->findModel($id),
    //        ]);
    //    }

    /**
     * Creates a new Operate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $all = AuthMenu::find()->select(['id', 'name', 'description', 'sort', 'display','parent_id'])->orderBy('sort')->asArray()->all();

        $operate = Operate::find()->column();

        $params = Yii::$app->request->post('operate_id',[]);

        if (!empty($params))
        {
            Operate::deleteAll();
            foreach ($params as $operate_id)
            {
                $model = new Operate();
                $data = explode('-',$operate_id);

                if(!empty($data))
                {
                    $model->id = $data[0];
                    $model->save();
                }
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'all' => $all,
                'operate' => $operate
            ]);
        }
    }

    /**
     * Updates an existing Operate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    //    public function actionUpdate($id)
    //    {
    //        $model = $this->findModel($id);
    //
    //        if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //            return $this->redirect(['view', 'id' => $model->id]);
    //        } else {
    //            return $this->render('update', [
    //                'model' => $model,
    //            ]);
    //        }
    //    }

    /**
     * Deletes an existing Operate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $operate = AdminOperate::findOne($id);
        if(!empty($operate))
        {
            $operate->delete();
        }
        //        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Operate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Operate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
