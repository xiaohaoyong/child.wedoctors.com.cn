<?php

namespace databackend\models\user;

use Yii;

/**
 * This is the model class for table "{{%child_info}}".
 * @property string $id
 * @property string $userid
 * @property string $name
 * @property integer $birthday
 * @property string $createtime
 */
class ChildInfo extends \common\models\ChildInfo
{
    public  static function find()
    {
        if(\Yii::$app->user->identity->type != 1)
        {
            return parent::find()->andFilterWhere(['`child_info`.source'=>\Yii::$app->user->identity->hospital]);
        }
        return parent::find();
    }
}
