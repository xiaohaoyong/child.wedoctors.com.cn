<?php

namespace hospital\controllers;

use common\models\HospitalAppointWeek;
use Yii;
use common\models\HospitalAppoint;
use hospital\models\HospitalAppointSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HospitalAppointController implements the CRUD actions for HospitalAppoint model.
 */
class HospitalAppointController extends Controller
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
     * Lists all HospitalAppoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HospitalAppointSearchModels();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HospitalAppoint model.
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
     * Creates a new HospitalAppoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type)
    {
        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);
        $model=HospitalAppoint::findOne(['doctorid'=>$doctor->userid,'type'=>$type]);
        $model=$model?$model:new HospitalAppoint();
        $model->doctorid=$doctor->userid;
        $model->type=$type;

        $post=Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $num=$post['num'];
            foreach($num as $k=>$v){
                foreach($v as $vk=>$vv) {
                    $rs=[];
                    $rs[]=$k;
                    $rs[]=$vk;
                    $rs[]=$vv;
                    $rs[]=$model->id;
                    $nums[]=$rs;
                }
            }
            Yii::$app->db->createCommand()->batchInsert(HospitalAppointWeek::tableName(), ['week','time_type','num','haid'],
                $nums
            )->execute();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $hospitalAppointWeek=HospitalAppointWeek::findAll(['haid'=>$model->id]);
            $nums=[];
            if($hospitalAppointWeek) {
                foreach ($hospitalAppointWeek as $k => $v) {
                    $nums[$v->week][$v->time_type]=$v->num;
                }
            }
            return $this->render('create', [
                'model' => $model,
                'nums'=>$nums,
            ]);
        }
    }

    /**
     * Updates an existing HospitalAppoint model.
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
     * Deletes an existing HospitalAppoint model.
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
     * Finds the HospitalAppoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HospitalAppoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HospitalAppoint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
