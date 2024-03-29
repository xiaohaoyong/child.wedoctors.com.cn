<?php

namespace frontend\controllers;

use common\models\AppointHpv;
use Yii;
use common\models\Appoint;
use hospital\models\AppointHpvSearch;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\UploadForm;
use common\models\AppointHpvSetting;
use common\models\Vaccine;
use yii\web\UploadedFile;
/**
 * AppointHpvController implements the CRUD actions for Appoint model.
 */
class AppointHpvController extends Controller
{
    /**
     * {@inheritdoc}
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

    public function actionForm($doctorid=206262){
        $model = new AppointHpv();
        $setting = AppointHpvSetting::findOne(['doctorid'=>$doctorid]);
        if($setting->vid){
            $vid = explode(',',$setting->vid);
            $vids = Vaccine::find()->indexBy('id')->select('name')->where(['id'=>$vid])->column();
        }else{
            $vids = [
                43=> '双价宫颈癌疫苗（第一剂）',
             50=>'双价宫颈癌疫苗（第二剂）',
             51=>'双价宫颈癌疫苗（第三剂）',
             54=>'四价宫颈癌疫苗（第一剂）',
             55=>'四价宫颈癌疫苗（第二剂）',
             56=>'四价宫颈癌疫苗（第三剂）',
             57=>'九价宫颈癌疫苗（第一剂）',
             58=>'九价宫颈癌疫苗（第二剂）',
             59=>'九价宫颈癌疫苗（第三剂）',
         ];
        }
        if ($model->load(Yii::$app->request->post()) ) {
            $model->userid=$this->login->userid;
            $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($model,'img'));
            if($imagesFile) {
                $upload= new UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
                $model->img = $image[0];
            }
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('form', [
            'vids' => $vids,
            'model' => $model,
            'doctorid'=>$doctorid,
        ]);
    }
    public function actionMy(){
        $model=AppointHpv::findAll(['userid'=>$this->login->userid]);
        return $this->render('my', [
            'model' => $model,
        ]);
    }

    /**
     * Lists all Appoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppointHpvSearch();
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
     * @throws NotFoundHttpException if the model cannot be found
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
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Appoint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Appoint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
        if (($model = AppointHpv::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
