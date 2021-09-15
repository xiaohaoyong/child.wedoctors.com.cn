<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/28
 * Time: 下午2:13
 */

namespace frontend\controllers;


use common\components\UploadForm;
use common\models\HealthRecords;
use common\models\HealthRecordsSchool;
use OSS\OssClient;
use yii\web\Response;
use yii\web\UploadedFile;

class HealthRecordsController extends Controller
{
    public function actionDown(){
        $model=HealthRecords::findOne(['userid'=>$this->login->id]);

        return $this->render('down',[
            'model'=>$model,
        ]);
    }
    public function actionForm($doctorid,$type=1){
        $healthRecords=HealthRecords::findOne(['userid'=>$this->login->id]);
        if($healthRecords && $healthRecords->field33){
            return $this->redirect(['done']);
        }

        $model = $healthRecords?$healthRecords:new HealthRecords();
        $model->scenario = 'form2';

        if ($model->load(\Yii::$app->request->post())) {
            $model->userid = $this->login->id;
            $model->doctorid = $doctorid;
            if ($model->save()) {
                return $this->redirect(['form1', 'doctorid' => $doctorid]);
            }
        }

        return $this->render('form', [
            'doctorid'=>$doctorid,
            'model' => $model,
            'type'=>$type,
        ]);
    }
    public function actionForm1($doctorid){
        $model=HealthRecords::findOne(['userid'=>$this->login->id]);
        $model=$model?$model:new HealthRecords();

        if ($model->load(\Yii::$app->request->post())) {
            $model->userid = $this->login->id;
            $model->doctorid = $doctorid;
            if ($model->save()) {
                return $this->redirect(['sign', 'id' => $model->id]);
            }
        }
        return $this->render('form1', [
            'doctorid'=>$doctorid,
            'model' => $model,
        ]);
    }
    public function actionForm2($doctorid){
        $model=HealthRecords::findOne(['userid'=>$this->login->id]);
        $model=$model?$model:new HealthRecords();
        $model->scenario = 'form1';
        $model->field43=1;

        if ($model->load(\Yii::$app->request->post())) {
            $model->userid = $this->login->id;
            $model->doctorid = $doctorid;
            if ($model->save()) {
                return $this->redirect(['sign', 'id' => $model->id]);
            }
        }
        return $this->render('form2', [
            'doctorid'=>$doctorid,
            'model' => $model,
        ]);
    }

    public function actionSign($type='',$id=0,$sign=''){
        if($type && $id && $sign){
            $sign1=md5($type.$id.HealthRecordsSchool::$typeSign[$type].date('Ymd'));
            if($sign1!=$sign){
                return $this->render('false');
            }
        }else {
            $healthRecords = HealthRecords::findOne(['userid' => $this->login->id]);

            if ($healthRecords && $healthRecords->field33) {
                return $this->redirect(['done']);
            } elseif (!$healthRecords) {
                return $this->redirect(['form']);
            }
        }

        return $this->render('sign',[
            'type'=>$type,
            'id'=>$id,
            'sign'=>$sign,
        ]);
    }

    public function actionSave($type='',$id=0,$sign=''){
        if($type && $id && $sign){
            $sign1=md5($type.$id.HealthRecordsSchool::$typeSign[$type].date('Ymd'));
            if($sign1!=$sign){
                return $this->render('false');
            }
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $image_data = json_decode(file_get_contents('php://input'), true);
            $healthRecordsSchool=HealthRecordsSchool::findOne($id);
            if($healthRecordsSchool && $image_data) {
                $baseimage = base64_decode(rawurldecode($image_data['image_data']));
                $time = time();
                $filen = substr(md5($time . rand(10, 100)), 4, 14);
                $images = \Yii::$app->params['imageUrl'] . $filen . '.' . UploadForm::filetype2($baseimage);
                $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-qingdao.aliyuncs.com');
                $ossClient->putObject('childimage', 'upload/' . $filen . '.' . UploadForm::filetype2($baseimage), $baseimage);
                $field=HealthRecordsSchool::$typeA[$type];
                $healthRecordsSchool->$field=$images;
                $healthRecordsSchool->save();
                var_dump($healthRecordsSchool->firstErrors);exit;
            }


        }else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $image_data = json_decode(file_get_contents('php://input'), true);
            $healthRecords=HealthRecords::findOne(['userid'=>$this->login->id]);
            if($healthRecords && $image_data) {
                $baseimage = base64_decode(rawurldecode($image_data['image_data']));
                $time = time();
                $filen = substr(md5($time . rand(10, 100)), 4, 14);
                $images = \Yii::$app->params['imageUrl'] . $filen . '.' . UploadForm::filetype2($baseimage);
                $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-qingdao.aliyuncs.com');
                $ossClient->putObject('childimage', 'upload/' . $filen . '.' . UploadForm::filetype2($baseimage), $baseimage);
                $healthRecords->field33=$images;
                $healthRecords->save();

            }
        }



        return ['code'=>10000,'msg'=>'成功'];
    }
    public function actionDone(){

        return $this->render('done');
    }
}