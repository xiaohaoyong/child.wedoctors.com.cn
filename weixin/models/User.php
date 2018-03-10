<?php

namespace weixin\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property integer $phone
 * @property integer $level
 * @property integer $type
 * @property string $createtime
 */
class User extends \common\models\User {

    /**
     * 添加
     * @param array $data  
     * @return array 对象 
     * slx
     */
    public static function addData($data) {
        $model = new self();
        if ($model->load($data)) {
            $model->save();
        }
        return $model;
    }

    /**
     * 手机查用户
     * @param string $phone
     * @return 对象
     */
    public static function findByPhone($phone) {
        return static::findOne(['phone' => $phone]);
    }

    /**
     * ID查用户
     * @param string $id
     * @return 对象
     */
    public static function findById($id) {
        return static::findOne($id);
    }

}
