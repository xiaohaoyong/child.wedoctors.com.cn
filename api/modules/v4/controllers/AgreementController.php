<?php
namespace api\modules\v4\controllers;

use api\controllers\Controller;
use common\models\Agreement;
use common\models\DoctorParent;
use common\models\UserDoctor;

class AgreementController extends Controller
{
    public function actionContent(){

        $doctorParent = DoctorParent::findOne(['parentid' => $this->userid]);
        $doctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
        $agreement = Agreement::find()->where(['doctorid'=>$doctor->userid])->one();
        if(!$agreement){
            $agreement = Agreement::find()->where(['county'=>$doctor->county])->one();
            if(!$agreement){
                $agreement = Agreement::find()->where(['county'=>0])->andWhere(['doctorid'=>0])->orderBy('id desc')->one();
            }
        }
        return ['content'=>$agreement->content];
    }

}
?>