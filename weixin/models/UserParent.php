<?php

namespace weixin\models;

use Yii;

/**
 * This is the model class for table "{{%user_parent}}".
 *
 * @property string $userid
 * @property string $mother
 * @property string $mother_phone
 * @property string $father
 * @property string $father_phone
 */
class UserParent extends \common\models\UserParent {

    /**
     * 添加/修改
     * @param array $data  
     * @return array 对象 
     * slx
     */
    public static function addData($data, $userid = NULL) {
        $model = self::findOne(['userid' => $userid]);
        if (empty($model)) {
            $model = new self();
        }
        if ($model->load($data)) {
            $model->save();
        }
        return $model;
    }

    /**
     * 儿童id获取信息
     * @param array $userid 
     * @return array 对象 
     * slx
     */
    public static function GetInfoByChildid($userid) {
        return UserParent::findOne(['userid' => $userid]);
    }

}
