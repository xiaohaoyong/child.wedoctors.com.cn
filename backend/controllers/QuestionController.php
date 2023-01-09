<?php

namespace backend\controllers;

use backend\models\QuestionReplySearch;
use common\helpers\WechatSendTmp;
use common\models\QuestionReply;
use common\models\UserDoctor;
use common\models\UserLogin;
use Yii;
use common\models\Question;
use common\models\QuestionImg;
use backend\models\QuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
    public function actionReply($id){
        $searchModel = new QuestionReplySearch();
        $params=Yii::$app->request->queryParams;
        $params['QuestionReplySearch']['qid']=$id;
        $dataProvider = $searchModel->search($params);
        $reply = new QuestionReply();
        $model= $this->findModel($id);
        $questionImg = QuestionImg::find()->where(['qid'=>$id])->select('image')->column();
        if( $reply->load(Yii::$app->request->post())){
            if($reply->save()){
                $model->state=1;
                $model->save();
                $doctor=UserDoctor::findOne(['userid'=>47156]);
                $userLogin=UserLogin::findOne(['id'=>$model->loginid]);
                $data = [
                    'name1' => ARRAY('value' => $doctor->name),
                    'time2' => ARRAY('value' => date('Y年m月d日 H:i',$reply->createtime)),
                    'thing3' => ARRAY('value' => $reply->content),
                ];
                $rs=WechatSendTmp::sendSubscribe($data,$userLogin->xopenid,'6bX1akpJdtHYW85-soUk-6c37wkqeu7RF7x02PSFuZ0','/pages/question/view?id='.$model->id);
            }
            return $this->redirect(['reply', 'id' => $id]);
        }

        return $this->render('reply', [
            'model' =>$model,
            'dataProvider' => $dataProvider,
            'reply'=>$reply,
	        'questionImg' =>$questionImg //回复图片
        ]);
    }

    /**
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Question();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
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
     * Deletes an existing Question model.
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
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
