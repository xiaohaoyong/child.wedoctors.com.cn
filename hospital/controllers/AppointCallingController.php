<?php

namespace hospital\controllers;

use common\helpers\baidu_tts\AipSpeech;
use common\models\Appoint;
use common\models\AppointCallingList;
use common\models\HospitalAppoint;
use common\models\queuing\Queue;
use common\models\UserDoctor;
use EasyWeChat\Factory;
use hospital\models\AppointSearchModels;
use Yii;
use common\models\AppointCalling;
use hospital\models\AppointCallingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AppointCallingController implements the CRUD actions for AppointCalling model.
 */
class AppointCallingController extends BaseController
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
     * Lists all AppointCalling models.
     * @return mixed
     */
    public function actionIndex()
    {
        $userDoctor=UserDoctor::findOne(['userid'=>\Yii::$app->user->identity->doctorid]);
        return $this->render('index', [
            'userDoctor' => $userDoctor,
        ]);
    }

    /**
     * Displays a single AppointCalling model.
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
     * Creates a new AppointCalling model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "@hospital/views/layouts/main-login.php";


        $model = AppointCalling::findOne(['userid' => Yii::$app->user->identity->userid, 'doctorid' => Yii::$app->user->identity->doctorid]);
        $model = $model ? $model : new AppointCalling();

        if ($model->load(Yii::$app->request->post())) {
            $model->userid = Yii::$app->user->identity->userid;
            $model->doctorid = Yii::$app->user->identity->doctorid;
            if ($model->save()) {
                return $this->redirect(['room']);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionRoom()
    {
        $this->layout = "@hospital/views/layouts/main-login.php";

        $model = AppointCalling::findOne(['userid' => Yii::$app->user->identity->userid, 'doctorid' => Yii::$app->user->identity->doctorid]);
        if (!$model) {
            return $this->redirect('/appoint-calling/create');
        }

        $appointCallingList = AppointCallingList::find()->where(['acid' => $model->id])
            ->andWhere(['>', 'createtime', strtotime('today')])
            ->andWhere(['<', 'createtime', strtotime('+1 day')])
            ->orderBy('state asc,id asc')
            ->one();
        if($appointCallingList){
            $appoint=Appoint::findOne($appointCallingList->aid);
        }
        return $this->render('room', [
            'appointCallingList'=>$appointCallingList,
            'appoint' => $appoint,
        ]);
    }

    public function actionState($id = 0, $state = 3)
    {
        $model = AppointCalling::findOne(['userid' => Yii::$app->user->identity->userid, 'doctorid' => Yii::$app->user->identity->doctorid]);
        if (!$model) {
            return $this->redirect('appoint-calling/create');
        }

        if($id){
            $ord_appointCallingList = AppointCallingList::findOne($id);
            if ($state != 4) {
                $ord_appointCallingList->state = $state;
                $ord_appointCallingList->save();
            }
        }

        if($state==3) {
            $appointCallingList=$this->calling($model);
            if(!$appointCallingList){
                \Yii::$app->getSession()->setFlash('error', '暂无患者排队！');
            }
            //上一个预约标记为完成
            if ($ord_appointCallingList) {
                if($ord_appointCallingList->aid) {
                    $appoint = Appoint::findOne($ord_appointCallingList->aid);
                    $appoint->state = 2;
                    $appoint->save();
                }
                $queue = new Queue(Yii::$app->user->identity->doctorid,$ord_appointCallingList->type, $ord_appointCallingList->time,$model->type?false:true);
                $queue->lrem($ord_appointCallingList->id);
                if($model->type==0){
                    $queue = new Queue(Yii::$app->user->identity->doctorid,$ord_appointCallingList->type, $ord_appointCallingList->time);
                    $queue->lpush($ord_appointCallingList->id);
                    $ord_appointCallingList->state = 0;
                    $ord_appointCallingList->acid = 0;
                    $ord_appointCallingList->calling = 0;
                    $ord_appointCallingList->save();

                }
            }
        }elseif ($state==2){
            if($id) {
                $appointCallingList=$this->calling($model);
                if($appointCallingList){
                    if ($ord_appointCallingList) {
                        $queue = new Queue(Yii::$app->user->identity->doctorid,$ord_appointCallingList->type, $ord_appointCallingList->time);
                        $queue->lrem($ord_appointCallingList->id);

                        $timeType=Appoint::getTimeTypeTmp($ord_appointCallingList->doctorid,$ord_appointCallingList->type);
                        $ord_appointCallingList->state=1;
                        $ord_appointCallingList->time=Appoint::getTimeTypeTmp($ord_appointCallingList->doctorid,$ord_appointCallingList->type);
                        if($ord_appointCallingList->save())
                        {
                            $queue = new Queue($ord_appointCallingList->doctorid, $ord_appointCallingList->type, $timeType);
                            $queue->lpush($ord_appointCallingList->id);
                        }
                    }
                }else{
                    \Yii::$app->getSession()->setFlash('error', '暂无患者排队！');
                }
            }else{
                \Yii::$app->getSession()->setFlash('error', '暂无患者排队！');
            }
        }elseif ($state == 4) {
            if($ord_appointCallingList) {
                $ord_appointCallingList->calling=1;
                $ord_appointCallingList->save();
                \Yii::$app->getSession()->setFlash('success', '已发送！');
            }else{
                \Yii::$app->getSession()->setFlash('error', '暂无患者需要提醒！');
            }

        }
        return $this->redirect(['room']);
    }

    /**
     * 叫号操作
     * @param $AppointCalling
     * @return AppointCallingList|null
     */
    public function calling($AppointCalling){
        $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $AppointCalling->doctorid, 'type' => $AppointCalling->type]);
        $timeType = Appoint::getTimeType($hospitalAppoint->interval, date('H:i'));
        $aclid=$this->queue($AppointCalling->type,$timeType,$hospitalAppoint,$AppointCalling->type?false:true);
        if ($aclid) {
            $appointCallingList = AppointCallingList::findOne($aclid);
            if($appointCallingList) {
                $appointCallingList->acid = $AppointCalling->id;
                $appointCallingList->calling = 1;
                $appointCallingList->save();
            }
            if($AppointCalling->type) {
                $redis = \Yii::$app->rd;
                $num = $appointCallingList->time . \common\models\AppointCallingList::listName($appointCallingList->id, $appointCallingList->doctorid, $appointCallingList->type, $appointCallingList->time);
                if (!$appointCallingList->aid) {
                    $name = "临时";
                } else {
                    $appoint = \common\models\Appoint::findOne($appointCallingList->aid);
                    $name = $appoint->name();
                }
                if ($appointCallingList->acid) {
                    $appointCalling = \common\models\AppointCalling::findOne($appointCallingList->acid);
                    $zname = $appointCalling->name;
                } else {
                    $zname = "待定";
                }
                $text = "请{$num}号 {$name} 到{$zname}就诊";
                $redis->lpush('Queue-ping-'.Yii::$app->user->identity->doctorid, $AppointCalling->type . "-" . $text);
            }

        }
        return $appointCallingList;
    }

    /**
     * 出号操作
     * @param $type
     * @param $timeType
     * @return mixed
     */
    public function queue($type,$timeType,$hospitalAppoint,$fenzhen=false){
        $queue = new Queue(Yii::$app->user->identity->doctorid,$type, $timeType,$fenzhen);
        $aclid = $queue->rpop();
        if (!$aclid) {
            foreach(Appoint::$timeText1 as $k=>$v){
                $queue = new Queue(Yii::$app->user->identity->doctorid,$type, $k,$fenzhen);
                $aclid = $queue->rpop();
                if($aclid){
                    break;
                }
            }
        }
        return $aclid;
    }

    public function actionDone($type = 1)
    {
        $model = AppointCalling::findOne(['userid' => Yii::$app->user->identity->userid, 'doctorid' => Yii::$app->user->identity->doctorid]);
        if ($type == 1) {
            $model->updatetime = time();
            $model->save();
        } else {
            $model->updatetime = 0;
            $model->save();
        }
        return $this->redirect(['room']);
    }


    /**
     * Updates an existing AppointCalling model.
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
     * Deletes an existing AppointCalling model.
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
     * Finds the AppointCalling model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppointCalling the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppointCalling::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList($doctorid=590848,$type){
        error_reporting(E_ALL ^ E_NOTICE);

        $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        $timeType = Appoint::getTimeType($hospitalAppoint->interval, date('H:i'));
        //当前时间段排队
        $queue = new Queue($doctorid, $type, $timeType,$type?false:true);
        $list[] = $queue->lrange();

        //其他时间段排队
        foreach(Appoint::$timeText as $k=>$v){
            if($k!=$timeType) {
                $queue = new Queue($doctorid, $type, $k,$type?false:true);
                $list[] = $queue->lrange();
            }
        }
        //临时号排队
        $queue = new Queue($doctorid, $type, 0,$type?false:true);
        $list[] = $queue->lrange();

        $this->layout = "@hospital/views/layouts/main-login.php";

        return $this->render('list',['doctorid'=>$doctorid,'list'=>$list,'time'=>date("h:i:s"),'type'=>$type]);
    }
    public function actionListNew($doctorid=590848,$type){

        error_reporting(E_ALL ^ E_NOTICE);
        \Yii::$app->response->format=Response::FORMAT_JSON;

        $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $doctorid, 'type' => $type]);
        $timeType = Appoint::getTimeType($hospitalAppoint->interval, date('H:i'));
        //当前时间段排队
        $queue = new Queue($doctorid, $type, $timeType,$type?false:true);
        $list[] = $queue->lrange();

        //其他时间段排队
        foreach(Appoint::$timeText as $k=>$v){
            if($k!=$timeType) {
                $queue = new Queue($doctorid, $type, $k,$type?false:true);
                $list[] = $queue->lrange();
            }
        }
        //临时号排队
        $queue = new Queue($doctorid, $type, 0,$type?false:true);
        $list[] = $queue->lrange();
        $list = [
            ['num'=>'1号','name'=>'汪振','zname'=>'第四窗口','times'=>'临时','is_read'=>1],
            ['num'=>'2号','name'=>'汪振1','zname'=>'第四窗口','times'=>'临时','is_read'=>0],
            ['num'=>'3号','name'=>'汪振2','zname'=>'第四窗口','times'=>'临时','is_read'=>0],
            ['num'=>'4号','name'=>'汪振3','zname'=>'第四窗口','times'=>'临时','is_read'=>0],
            ['num'=>'5号','name'=>'汪振4','zname'=>'第四窗口','times'=>'临时','is_read'=>0],

        ];

        return ['lists'=>$list];

    }
    public function actionText(){
        $this->layout = "@hospital/views/layouts/main-login.php";

        return $this->render('text');
    }
    public function actionTtl($text,$id){
        \Yii::$app->response->format=Response::FORMAT_JSON;
            $appointCallingList=AppointCallingList::findOne($id);
        if($appointCallingList->calling==1) {
            $appointCallingList->calling = 0;
            $appointCallingList->save();
            return ['code'=>10000];
        }else{
            return ['code'=>20000];
        }
    }

    public function actionIspush($id){
        $userDoctor=UserDoctor::findOne(['userid'=>\Yii::$app->user->identity->doctorid]);
        $userDoctor->is_zhenshi=$id;
        $userDoctor->save();
    }
}
