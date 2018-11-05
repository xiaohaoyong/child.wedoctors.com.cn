<?php

namespace hospital\controllers;

use common\models\UserDoctor;
use Yii;
use common\models\UserDoctorAppoint;
use hospital\models\UserDoctorAppointSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserDoctorAppointController implements the CRUD actions for UserDoctorAppoint model.
 */
class UserDoctorAppointController extends Controller
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
     * Lists all UserDoctorAppoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);

        $types=[];
        if($doctor->appoint){
            $types=str_split((string)$doctor->appoint);
        }
        $userDoctorAppoint=UserDoctorAppoint::find()->select('type')
            ->andFilterWhere(['doctorid'=>$doctor->userid])
            ->column();
        return $this->render('index', [
            'types' => $types,
            'userDoctorAppoint' => $userDoctorAppoint,
        ]);
    }

    /**
     * Displays a single UserDoctorAppoint model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        $doctorid=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);
        $model= UserDoctorAppoint::findOne(['doctorid'=>$doctorid]);
        if($model->weeks){
            $model->weeks=str_split((string)$model->weeks);
        }
        $model->weeks="星期".implode(',',$model->weeks);
        $model->cycle=\common\models\UserDoctorAppoint::$cycleText[$model->cycle];
        $model->delay=$model->delay."天";
        $model->type1_num=$model->type1_num."人";
        $model->type2_num=$model->type2_num."人";
        $model->type3_num=$model->type3_num."人";
        $model->type4_num=$model->type4_num."人";
        $model->type5_num=$model->type5_num."人";
        $model->type6_num=$model->type6_num."人";

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new UserDoctorAppoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserDoctorAppoint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->doctorid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserDoctorAppoint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($type)
    {
        $doctorid=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);
        $model= UserDoctorAppoint::findOne(['doctorid'=>$doctorid,'type'=>$type]);
        $model = $model?$model:new UserDoctorAppoint();
        $model->doctorid=$doctorid;
        $model->type=$type;

        if($model->weeks){
            $model->week=str_split((string)$model->weeks);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->doctorid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserDoctorAppoint model.
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
     * Finds the UserDoctorAppoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserDoctorAppoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserDoctorAppoint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
