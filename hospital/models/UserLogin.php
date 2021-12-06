<?php
namespace hospital\models;
use yii\db\ActiveRecord;

/**
 * Class User
 *
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $type
 * @property integer $hospital
 * @property integer $province
 * @property integer $county
 * @property integer $city
 * @property integer $careatetime
 * @property integer $lasttime
 * @package app\models
 */
class UserLogin extends \common\models\UserLogin implements \yii\web\IdentityInterface
{

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return self::findOne(['accessToken'=>$token]);
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        $userLogin=self::findOne($this->id);
        return $userLogin->auth_key;
    }

    public function validateAuthKey($authKey){
        $userLogin=self::findOne($this->id);
        return $userLogin->auth_key === $authKey;
    }
    public static function findByPhone($phone)
    {
        $userLogin=self::findOne(['phone'=>$phone,'type'=>1]);
        return $userLogin;
    }
}

