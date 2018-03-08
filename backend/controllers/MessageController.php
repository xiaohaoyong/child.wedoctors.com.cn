<?php

namespace backend\controllers;

use callmez\wechat\sdk\MpWechat;
use common\helpers\WechatSendTmp;
use Yii;
use common\models\Message;
use databackend\models\message\MessageSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends BaseController
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
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Message model.
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
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new \databackend\models\message\Message();

        if ($model->load(Yii::$app->request->post())) {
            $model->userid=Yii::$app->user->id;
            if( $model->save()) {

                //微信模板消息
                $data = [
                    'first' => array('value' => $model->title."\n",),
                    'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
                    'keyword2' => ARRAY('value' => $model->content,),
                    'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
                    ];
                $touser = "o5ODa0451fMb_sJ1D1T4YhYXDOcg";
                $url = $model->url;

                if (WechatSendTmp::send($data, $touser, \Yii::$app->params['yiyuan'], $url)) {
                    \Yii::$app->getSession()->setFlash('success', "发送成功");
                } else {
                    \Yii::$app->getSession()->setFlash('error', "发送失败");
                }

                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Message model.
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
     * Deletes an existing Message model.
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
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
