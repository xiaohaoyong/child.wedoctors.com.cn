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
class Doctors extends \common\models\Doctors implements \yii\web\IdentityInterface
{

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return \common\models\UserLogin::findOne(['accessToken'=>$token]);
    }

    public function getId(){
        return $this->userid;
    }

    public function getAuthKey(){
        $userLogin=\common\models\UserLogin::findOne(['userid'=>$this->id]);
        return $userLogin->auth_key;
    }

    public function validateAuthKey($authKey){
        $userLogin=\common\models\UserLogin::findOne(['userid'=>$this->id]);

        return $userLogin->auth_key === $authKey;
    }
    public static function findByPhone($phone)
    {
        return \common\models\User::findOne(['phone'=>$phone]);
    }
}

?>

