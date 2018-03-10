<?php

namespace databackend\models\user;

use Yii;

/**
 * This is the model class for table "doctor_parent".
 *
 * @property integer $id
 * @property integer $doctorid
 * @property integer $parentid
 * @property integer $createtime
 * @property integer $level
 */
class DoctorParent extends \common\models\DoctorParent
{
    public  static function find()
    {
        if(\Yii::$app->user->identity->type != 1)
        {
            $doctorid=UserDoctor::findOne(['hospitalid'=>\Yii::$app->user->identity->hospital])->userid;
            return parent::find()->andFilterWhere(['doctorid'=>$doctorid]); // TODO: Change the autogenerated stub
        }
        return parent::find();
    }
}
