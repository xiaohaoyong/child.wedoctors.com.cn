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
use OSS\OssClient;
use yii\web\Response;
use yii\web\UploadedFile;

class HealthRecordsController extends Controller
{
    public function actionDown($id){
        $model=HealthRecords::findOne($id);

        return $this->renderPartial('down',[
            'model'=>$model,
        ]);
    }
    public function actionForm($doctorid){
        $healthRecords=HealthRecords::findOne(['userid'=>$this->login->id]);
        if($healthRecords && $healthRecords->field33){
            return $this->redirect(['done']);
        }

        $model = $healthRecords?$healthRecords:new HealthRecords();
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
        ]);
    }
    public function actionForm1($doctorid){
        $model=HealthRecords::findOne(['userid'=>$this->login->id]);

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

    public function actionSign(){
//        $healthRecords=HealthRecords::find()->where(['userid'=>$this->login->id])->andWhere(['!=','field33',''])->one();
//
//        if($healthRecords){
//            return $this->redirect(['done']);
//        }

        return $this->render('sign');
    }

    public function actionSave(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $image_data = json_decode(file_get_contents('php://input'), true);
        $healthRecords=HealthRecords::findOne(['userid'=>573886]);
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
        return ['code'=>10000,'msg'=>'成功'];
    }
    public function actionDone(){

        return $this->render('done');
    }
}