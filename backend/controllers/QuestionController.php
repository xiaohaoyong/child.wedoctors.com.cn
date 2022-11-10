<?php

namespace backend\controllers;

use backend\models\QuestionReplySearch;
use common\helpers\WechatSendTmp;
use common\models\QuestionReply;
use common\models\UserDoctor;
use common\models\UserLogin;
use Yii;
use common\models\Question;
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

    /**
     * 更改问题状态-已结束
     */
    public function actionUpdateState(){

        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        if($model){

            //发送评价消息
            $thing1 = '在线咨询';
            // $thing2 = '您向'.$userDoctor->name.'的在线咨询已结束，邀请您对医生的回复进行评价';
            $thing2 = '邀请您对本次咨询进行评价';
            $data = [
                'thing1' => ARRAY('value' => $thing1),
                'thing2' => ARRAY('value' => $thing2),
                'time3' => ARRAY('value' => date('Y年m月d日 H:i',time())),
            ];
            $userLogin = UserLogin::find()->where(['userid'=>$model->userid])->one();
            $rs=WechatSendTmp::sendSubscribe($data,$userLogin->xopenid,'cJqc11RdX95akxICJmQo3nP-0yo6VA4eHAeZHjEViHo','/pages/question/view?id='.$model->id);

            Question::updateAll(['state'=>2],['id'=>$model->id]);
               return $this->redirect(['index']);
        }

    }
}
