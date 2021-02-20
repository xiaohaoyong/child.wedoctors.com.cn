<?php

namespace hospital\controllers;

use common\models\HospitalAppointMonth;
use common\models\HospitalAppointStreet;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointVaccineTimeNum;
use common\models\HospitalAppointWeek;
use Yii;
use common\models\HospitalAppoint;
use hospital\models\HospitalAppointSearchModels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HospitalAppointController implements the CRUD actions for HospitalAppoint model.
 */
class HospitalAppointController extends BaseController
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
        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);

        $types=[];
        if(strpos($doctor->appoint,',')!==false){
            $types = explode(',',$doctor->appoint);
        }elseif ($doctor->appoint) {
            $types = str_split((string)$doctor->appoint);
        }
        $userDoctorAppoint=HospitalAppoint::find()->select('type')
            ->andFilterWhere(['doctorid'=>$doctor->userid])
            ->column();

        if(Yii::$app->request->post()){
            $doctor->load(Yii::$app->request->post());
            if($doctor->save()){
                \Yii::$app->getSession()->setFlash('success','编辑成功');
            }else{
                \Yii::$app->getSession()->setFlash('error','编辑失败');
            }
        }

        return $this->render('index', [
            'types' => $types,
            'userDoctorAppoint' => $userDoctorAppoint,
            'model'=>$doctor
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
        if(!isset($model->delay)){
            $model->delay=0;
        }
        if($model->weeks){
            $model->week=str_split((string)$model->weeks);
        }
        if($model->non_date){
            $model->non_date=explode(',',$model->non_date);
        }
        if($model->sure_date){
            $model->sure_date=explode(',',$model->sure_date);
        }
        $post=Yii::$app->request->post();
        if($post['HospitalAppoint']['non_date']){
            $post['HospitalAppoint']['non_date']=implode(',',$post['HospitalAppoint']['non_date']);
        }
        if($post['HospitalAppoint']['sure_date']){
            $post['HospitalAppoint']['sure_date']=implode(',',$post['HospitalAppoint']['sure_date']);
        }


        if ($model->load($post) && $model->save()) {
            HospitalAppointWeek::deleteAll('haid='.$model->id);

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
            HospitalAppointVaccine::deleteAll('haid='.$model->id);
            if($post['vaccine']){

                foreach($post['vaccine'] as $vk=>$vv){
                    foreach ($vv as $vvk=>$vvv) {
                        $hav = new HospitalAppointVaccine();
                        $hav->vaccine = $vvv;
                        $hav->haid = $model->id;
                        $hav->week = $vk;
                        $hav->save();
                    }
                }
            }
            if($post['vaccine1']){

                foreach($post['vaccine1'] as $vk=>$vv){
                    foreach ($vv as $vvk=>$vvv) {
                        $hav = new HospitalAppointVaccine();
                        $hav->vaccine = $vvv;
                        $hav->haid = $model->id;
                        $hav->week = $vk;
                        $hav->type = 2;
                        $hav->save();
                    }
                }
            }


            if($post['vaccine_num']){
                HospitalAppointVaccineNum::deleteAll('haid='.$model->id);
                foreach($post['vaccine_num'] as $vk=>$vv){
                    foreach ($vv as $vvk=>$vvv) {
                        if(is_numeric($vvv)) {
                            $hav = new HospitalAppointVaccineNum();
                            $hav->vaccine = $vvk;
                            $hav->haid = $model->id;
                            $hav->week = $vk;
                            $hav->num = $vvv;
                            if($post['vaccine_num_type'][$vk][$vvk]){
                                $hav->type=1;
                            }
                            $hav->save();
                        }
                    }
                }
            }

            HospitalAppointStreet::deleteAll('haid='.$model->id);
            if($post['streets']){

                foreach($post['streets'] as $vk=>$vv){
                    foreach ($vv as $vvk=>$vvv) {
                        $hav = new HospitalAppointStreet();
                        $hav->street = $vvv;
                        $hav->haid = $model->id;
                        $hav->week = $vk;
                        $hav->save();
                    }
                }
            }
            if($post['month']){
                HospitalAppointMonth::deleteAll(['haid'=>$model->id]);
                foreach($post['month'] as $k=>$v){
                    foreach ($v as $vk=>$vv) {
                        $hospitalAppointMonth = new HospitalAppointMonth();
                        $hospitalAppointMonth->month = $vv;
                        $hospitalAppointMonth->haid = $model->id;
                        $hospitalAppointMonth->type = $k;
                        $hospitalAppointMonth->save();
                        var_dump($hospitalAppointMonth->firstErrors);
                    }
                }
            }

            return $this->redirect(['create', 'type' => $type]);
        } else {
            $hospitalAppointWeek=HospitalAppointWeek::findAll(['haid'=>$model->id]);
            $nums=[];
            if($hospitalAppointWeek) {
                foreach ($hospitalAppointWeek as $k => $v) {
                    $nums[$v->week][$v->time_type]=$v->num;
                }
            }
            $hospitalAppointMonth=HospitalAppointMonth::find()->select('month')->where(['haid'=>$model->id])->column();
            $hospitalAppointMonth=$hospitalAppointMonth?$hospitalAppointMonth:new HospitalAppointMonth();
            return $this->render('create', [
                'model' => $model,
                'nums'=>$nums,
                'type'=>$type,
                'hospitalAppointMonth'=>$hospitalAppointMonth,
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

    public function actionVaccineSave(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);

        $post=Yii::$app->request->post();
        HospitalAppointVaccineTimeNum::deleteAll(['vaccine'=>$post['vaccine'],'week'=>$post['week'],'type'=>$post['type'],'doctorid'=>$doctor->userid]);
        foreach($post['vaccine_num'] as $k=>$v){
            if($v>0) {
                $hospitalAppointMonth = new HospitalAppointVaccineTimeNum();
                $hospitalAppointMonth->type = $post['type'];
                $hospitalAppointMonth->doctorid = $doctor->userid;
                $hospitalAppointMonth->vaccine = $post['vaccine'];
                $hospitalAppointMonth->num = $v;
                $hospitalAppointMonth->appoint_time = $k;
                $hospitalAppointMonth->week = $post['week'];
                $hospitalAppointMonth->save();
            }
        }
        return ['code'=>10000];
    }

    public function actionVaccineNum($week,$type,$vaccine){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital]);
        $list=HospitalAppointVaccineTimeNum::findAll(['vaccine'=>$vaccine,'week'=>$week,'type'=>$type,'doctorid'=>$doctor->userid]);
        return ['list'=>$list];

    }
}
