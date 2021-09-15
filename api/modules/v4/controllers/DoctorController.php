<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/7/9
 * Time: 4:23 PM
 */

namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\components\Code;
use common\models\DoctorParentEdit;
use common\models\DoctorTeam;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\QuestionImg;
use common\models\UserDoctor;
use yii\web\UploadedFile;

class DoctorController extends \api\modules\v3\controllers\DoctorController
{
    public function actionEditParent(){
        $post = \Yii::$app->request->post();
        $imagesFile = UploadedFile::getInstancesByName('file');
        if($imagesFile) {
            $upload= new \common\components\UploadForm();
            $upload->imageFiles = $imagesFile;
            $image = $upload->upload();

            $hospital=Hospital::find()->where(['name'=>$post['HospitalName']])->orderBy('id desc')->one();
            if($hospital) {
                $doctor=UserDoctor::findOne(['hospitalid'=>$hospital->id]);
                if($doctor) {
                    $doctorParentEdit = new DoctorParentEdit();
                    $doctorParentEdit->doctorid = $doctor ->userid;
                    $doctorParentEdit->userid = $this->userid;
                    $doctorParentEdit->img = $image[0];
                    $doctorParentEdit->save();
                }

                if ($doctorParentEdit->firstErrors) {
                    return new Code(20010, $doctorParentEdit->firstErrors);
                }
            }else{
                return new Code(20010, '未查询到修改社区');
            }
        }
        return '';
    }

}