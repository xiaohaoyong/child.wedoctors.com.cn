<?php

namespace backend\models\operate;

use backend\models\AdminDb;
use backend\models\rbac\AuthAdminuser;
use backend\models\rbac\AuthMenu;
use Yii;

/**
 * This is the model class for table "{{%admin_operate}}".
 *
 * @property integer $id
 * @property integer $operate_id
 * @property integer $operate_time
 * @property integer $admin_id
 */
class AdminOperate extends AdminDb
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_operate}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operate_id', 'operate_time', 'admin_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operate_id' => '操作对象',
            'operate_time' => '操作时间',
            'admin_id' => '操作人',
        ];
    }

    public function getAdmin()
    {
        return $this->hasOne(AuthAdminuser::className(),['id' => 'admin_id']);
    }

    public function getMenu()
    {
        return $this->hasOne(AuthMenu::className(),['id' => 'operate_id']);
    }
}
