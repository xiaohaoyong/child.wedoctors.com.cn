<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/1/17
 * Time: 上午10:46
 */

namespace frontend\controllers;

use api\models\ChildInfo;
use common\components\Code;
use common\components\UploadForm;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Appoint;
use common\models\AppointAdult;
use common\models\Area;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointStreet;
use common\models\HospitalAppointVaccine;
use common\models\HospitalAppointVaccineNum;
use common\models\HospitalAppointVaccineTimeNum;
use common\models\HospitalAppointWeek;
use common\models\MotherArticle;
use common\models\MotherForm;
use common\models\Street;
use common\models\UserDoctor;
use common\models\Vaccine;
use EasyWeChat\Factory;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;


class MotherFormController extends Controller
{
    public function actionIndex($id){
        $model=MotherArticle::findOne($id);

        return $this->render('index',[
            'model' => $model,
            'userid'=>$this->login->userid,
            'id'=>$id,
        ]);
    }
    public function actionView($id){
        //$model=MotherForm::findOne(['id'=>$id,'userid'=>$this->login->userid]);

        return $this->render('view',[
            'model' => $model,
            'id'=>$id,
        ]);
    }
    public function actionForm($id=1){

        $model=MotherForm::findOne(['userid'=>$this->login->userid]);
        $model=$model?$model:new MotherForm();

        if ($model->load(\Yii::$app->request->post())) {
            $imagesFile = UploadedFile::getInstancesByName(Html::getInputName($model,'img'));
            if($imagesFile) {
                $upload= new UploadForm();
                $upload->imageFiles = $imagesFile;
                $image = $upload->upload();
                $model->idimg = $image[0];
            }
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        return $this->render('form', [
            'model' => $model,
            'userid'=>$this->login->userid,
        ]);
    }
    public function actionArea($id)
    {
        if(\Yii::$app->request->get('type')=='county'){
            $area = Area::$county[$id];
        }else{
            $area = Area::$city[$id];

        }
        echo Html::tag('option',Html::encode("请选择"),array('value'=>0));

        foreach($area as $k=>$v)
        {
            echo Html::tag('option',Html::encode($v),array('value'=>$k));
        }
    }
}