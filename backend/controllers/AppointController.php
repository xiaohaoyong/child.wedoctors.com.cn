<?php

namespace backend\controllers;

use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\ChildInfo;
use common\models\Log;
use common\models\UserDoctor;
use common\models\UserLogin;
use hospital\models\user\Hospital;
use Yii;
use common\models\Appoint;
use backend\models\AppointSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppointController implements the CRUD actions for Appoint model.
 */
class AppointController extends BaseController
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

    public function actionDone($id,$state=2)
    {

        $model = $this->findModel($id);
        $model->state = $state;
        if ($model->save()) {
            $login = UserLogin::findOne(['id' => $model->loginid]);

            if($state==3){
                $data = [
                    'first' => ['value' => '您的预约已经取消。'],
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => ARRAY('value' => $model->name()),
                    'keyword3' => ARRAY('value' => '预约异常'),
                    'remark' => ARRAY('value' => "该电话号码已多次替他人预约，社区医院要求四价九价疫苗由接种本人预约，目前已经取消该预约号，且预约号已经回到号源池，请接种本人用自己电话号码进行预约。感谢配合！"),
                ];
                $tmpid='t-fxuMyA77Xx71OA4_3y528hOSWXk_2rDjvN1zgefbk';
            }else{
                $data = [
                    'first' => ['value' => '服务已完成'],
                    'keyword1' => ARRAY('value' => Appoint::$typeText[$model->type]),
                    'keyword2' => ARRAY('value' => date('Y年m月d日 H:i:00')),
                    'remark' => ARRAY('value' => "感谢您对社区医院本次服务的支持，如有问题请联系在线客服"),

                ];
                $tmpid='oxn692SYkr2EIGlVIhYbS1C4Qd6FpmeYLbsFtyX45CA';
            }

            $rs = WechatSendTmp::send($data, $login->openid, $tmpid, '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=2',]);

        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPush($childid)
    {
        $model = new \common\models\Appoint();
        if ($post = Yii::$app->request->post()) {
            $model->load($post);
            $doctor=UserDoctor::findOne(['hospitalid'=>Yii::$app->user->identity->hospital]);
            $model->appoint_date=strtotime($model->date);
            $model->doctorid=$doctor->userid;
            $model->state = 5;
            $model->push_state=1;
            $model->mode=1;
            $log=new \common\components\Log('Appoint_Doctor_Push');
            if ($model->save()) {
                if ($model->loginid) {
                    $login = UserLogin::findOne(['id' => $model->loginid]);
                    if ($login->openid) {
                        $log->addLog("微信");

                        if($model->type==1){
                            $data = [
                                'first' => ['value' => "家长您好，该带宝宝来社区服务中心体检啦\n\n"."预约时间：".$model->date." ".Appoint::$timeText[$model->appoint_time]],
                                'keyword1' => ARRAY('value' => $doctor->name),
                                'keyword2' => ARRAY('value' => $doctor->phone),
                                'keyword3' => ARRAY('value' => $model->remark),
                                'remark' => ARRAY('value' => "\n需要点击此消息并确定领取凭证，到社区服务中心出示此凭证即可享受服务，为了宝宝的健康，请安排好您的时间哦"),
                            ];
                        }elseif($model->type==2){
                            $data = [
                                'first' => ['value' => "家长您好，该带宝宝来社区服务中心接种啦\n\n"."预约时间：".$model->date." ".Appoint::$timeText[$model->appoint_time]],
                                'keyword1' => ARRAY('value' => $doctor->name),
                                'keyword2' => ARRAY('value' => $doctor->phone),
                                'keyword3' => ARRAY('value' => $model->remark),
                                'remark' => ARRAY('value' => "\n需要点击此消息并确定领取凭证，到社区服务中心出示此凭证即可享受服务，为了宝宝的健康，请安排好您的时间哦"),
                            ];
                        }
                        $log->addLog($login->openid);

                        $rs = WechatSendTmp::send($data, $login->openid, '3ui_xwyZXEw4DK4Of5FRavHDziSw3kiUyeo74-B0grk', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => '/pages/appoint/my?type=1',]);
                        $log->addLog($rs);
                        if($rs){
                            $model->push_state=2;
                        }else{
                            $model->push_state=4;
                        }
                        $model->save();
                    }
                }
                if(!$login->openid) {
                    $log->addLog("短信");

                    $data['doctor'] = $doctor->name;
                    $data['phone'] = $doctor->phone;
                    $data['type'] = Appoint::$typeText1[$model->type];
                    $data['date_time'] = $model->date." ".explode('-',Appoint::$timeText[$model->appoint_time])[0];
                    //$data['time'] = Appoint::$timeText[$model->appoint_time];
                    $rs=SmsSend::appoint($data, $model->phone);
                    $log->addLog($rs?'true':'false');
                    if($rs){
                        $model->push_state=3;
                    }else{
                        $model->push_state=5;
                    }
                    $model->save();
                }
                $log->saveLog();
                return $this->redirect(['index']);
            }
        }


        return $this->render('push', [
            'childid' => $childid,
            'model' => $model,
        ]);
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
     * Lists all Appoint models.
     * @return mixed
     */
    public function actionIndext()
    {
        $searchModel = new AppointSearchModels();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indext', [
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
