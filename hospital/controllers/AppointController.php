<?php

namespace hospital\controllers;

use common\helpers\WechatSendTmp;
use common\models\UserLogin;
use Yii;
use common\models\Appoint;
use hospital\models\AppointSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppointController implements the CRUD actions for Appoint model.
 */
class AppointController extends Controller
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

    public function actionDone($id){

        $model=$this->findModel($id);
        $model->state=2;
        if($model->save()){

            $login=UserLogin::findOne(['id'=>$model->loginid]);
            $data = [
                'first'=>['value'=>'服务已完成'],
                'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                'keyword2' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                'remark' => ARRAY('value' => "感谢您对社区医院本次服务的支持，如有问题请联系在线客服"),

            ];
            $rs = WechatSendTmp::send($data,$login->openid, 'oxn692SYkr2EIGlVIhYbS1C4Qd6FpmeYLbsFtyX45CA','',['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=2',]);

        }

        return $this->redirect(['index']);
    }
    /**
     * Lists all Appoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppointSearchModels();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Appoint model.
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
     * Creates a new Appoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Appoint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Appoint model.
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
     * Deletes an existing Appoint model.
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
     * Finds the Appoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Appoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appoint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
