<?php

namespace weixin\models;

use Yii;

/**
 * This is the model class for table "{{%user_login}}".
 *
 * @property string $userid
 * @property string $password
 * @property string $openid
 */
class UserLogin extends \common\models\UserLogin {

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
     * 保存前处理事件
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            //判断password长度
            if (strlen($this->password) == 32) {
                $this->password = $this->password;
            } else {
                $this->password = md5(md5($this->password . \Yii::$app->params['passwordKey']));
            }
            return true;
        }
        return false;
    }

    /**
     * 更新
     * @param int $userid
     * @param array $attributes = array('password'=>'111');
     * @return array 对象 
     * slx
     */
    public static function updateData($userid, $attributes) {
        $model = self::findOne(['userid'=>$userid]);
        if (empty($model)) {
            return false;
        }
        $model->attributes = $attributes;
        $model->save();
        return $model;
    }

    /**
     * userid查用户
     *
     * @param string $userid
     * @return 对象
     */
    public static function findByUserid($userid) {
        return static::findOne(['userid' => $userid]);
    }

}
