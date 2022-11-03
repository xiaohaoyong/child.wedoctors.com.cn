<?php

namespace hospital\controllers;

use hospital\models\AppointCommentSearch;
use common\models\AppointComment;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * 预约就诊评价
 */
class AppointCommentController extends BaseController
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
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppointCommentSearch();
        $params=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        $id = intval(\Yii::$app->request->get('id'));
        $searchModel = new AppointCommentSearch();
        $params=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = AppointComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
