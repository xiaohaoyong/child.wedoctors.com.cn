<?php

namespace backend\models\operate;

use backend\models\AdminDb;
use backend\models\rbac\AuthMenu;
use Yii;

/**
 * This is the model class for table "{{%operate}}".
 *
 * @property integer $id
 * @property string $operate_name
 */
class Operate extends AdminDb
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    public function getMenu()
    {
        return $this->hasOne(AuthMenu::className(),['id' => 'id']);
    }
}
