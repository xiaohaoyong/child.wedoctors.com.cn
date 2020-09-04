<?php

namespace hospital\controllers;

use common\models\Appoint;
use common\models\AppointCallingList;
use EasyWeChat\Factory;
use hospital\models\AppointSearchModels;
use Yii;
use common\models\AppointCalling;
use hospital\models\AppointCallingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $searchModel = new AppointCallingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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


        $model = AppointCalling::findOne(['userid'=>Yii::$app->user->identity->userid,'doctorid'=>Yii::$app->user->identity->doctorid]);
        $model=$model?$model:new AppointCalling();

        if ($model->load(Yii::$app->request->post())) {
            $model->userid=Yii::$app->user->identity->userid;
            $model->doctorid=Yii::$app->user->identity->doctorid;
            if($model->save()){
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

        $model = AppointCalling::findOne(['userid'=>Yii::$app->user->identity->userid,'doctorid'=>Yii::$app->user->identity->doctorid]);
        if(!$model){
            return $this->redirect('appoint-calling/create');
        }

        $appointCallingList=AppointCallingList::find()->where(['acid'=>$model->id])
            ->select('aid')
            ->andWhere(['>', 'createtime', strtotime('today')])
            ->andWhere(['<', 'createtime', strtotime('+1 day')])
            ->orderBy('state asc,id asc')
            ->column();

        $searchModel = new AppointSearchModels();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy([new \yii\db\Expression('FIELD (id, '.implode(',',$appointCallingList).')')]);
        $dataProvider->query->andWhere(['in','id',$appointCallingList]);
        $dataProvider->setPagination(['pageSize'=>200]);

        //echo $dataProvider->query->createCommand()->getRawSql();exit;

        return $this->render('room',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }
    public function actionState($id,$state=3){
        $appointCallingList=AppointCallingList::findOne(['aid'=>$id]);
        $appointCallingList->state=$state;
        $appointCallingList->save();

        $appointCalling=AppointCalling::findOne($appointCallingList->acid);
        if($appointCalling->updatetime!=0) {
            $appointCalling->updatetime = time();
            $appointCalling->save();
        }
        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);



        if($state==4){
            $app->customer_service->message("您好，请前往".$appointCalling->name."就诊")->to($appointCallingList->openid)->send();
        }elseif($state==3){
            $appoint=Appoint::findOne($id);
            $appoint->state=2;
            $appoint->save();
            $openids=AppointCallingList::find()->where(['acid'=>$appointCallingList->acid])
                ->select('openid')
                ->andWhere(['>', 'createtime', strtotime('today')])
                ->andWhere(['<', 'createtime', strtotime('+1 day')])
                ->orderBy('state asc,id asc')
                ->column();
            if($openids[0]) {
                $app->customer_service->message("您好，请前往" . $appointCalling->name . "就诊")->to($openids[0])->send();
            }
        }
        return $this->redirect(['room']);
    }
    public function actionDone($type=1){
        $model = AppointCalling::findOne(['userid'=>Yii::$app->user->identity->userid,'doctorid'=>Yii::$app->user->identity->doctorid]);
        if($type==1) {
            $model->updatetime = time();
            $model->save();
        }else{
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
}
