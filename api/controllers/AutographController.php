<?php

namespace frontend\controllers;

use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Pregnancy;
use common\models\UserDoctor;
use common\models\UserParent;
use Yii;
use common\models\Autograph;
use hospital\models\AutographSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AutographController implements the CRUD actions for Autograph model.
 */
class AutographController extends \yii\web\Controller
{
    /**
     * Displays a single Autograph model.
     * @param integer $id
     * @return mixed
     */
    public function actionDown($userid,$type=0)
    {
        if($type == 1) {
            $userParent = UserParent::findOne(['userid' => $userid]);


            $doctorParent = DoctorParent::findOne(['parentid' => $userid]);
            $userDoctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
            $child = ChildInfo::find()->where(['userid' => $userid])->all();


            $view = Yii::$app->user->identity->hospitalid == 110647 ? 'down1' : 'down';
            return $this->renderPartial($view, [
                'userParent' => $userParent,
                'userid' => $userid,
                'userDoctor' => $userDoctor,
                'child' => $child,
            ]);
        }elseif($type == 2){
            $userParent = UserParent::findOne(['userid' => $userid]);
            $doctorParent = DoctorParent::findOne(['parentid' => $userid]);
            $userDoctor = UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
            $pregnancy = Pregnancy::findOne(['familyid'=>$userid]);
            return $this->renderPartial('downa', [
                'pregnancy' => $pregnancy,
                'userid' => $userid,
                'userParent' => $userParent,
                'userDoctor' => $userDoctor,

            ]);
        }
    }
}
