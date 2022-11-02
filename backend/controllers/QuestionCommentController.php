<?php

namespace backend\controllers;
use backend\models\QuestionCommentSearch;
use backend\models\QuestionReplySearch;
use common\models\QuestionComment;
use Yii;
use common\models\Question;
use backend\models\QuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * 问题评论
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionCommentController extends Controller
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
        $searchModel = new QuestionCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        $qid = intval(\Yii::$app->request->get('qid')); //评论ID
        $model = $this->findModel($qid);
        $searchModel = new QuestionReplySearch();
        $params=Yii::$app->request->queryParams;
        $params['QuestionReplySearch']['qid']= $model->qid; //问题ID
        $dataProvider = $searchModel->search($params);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QuestionComment::find()->where(['qid'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
