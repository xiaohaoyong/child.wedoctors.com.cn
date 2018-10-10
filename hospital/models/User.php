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
class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'data_user';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    public static function findByUsername($username)
    {     //①
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthkey()
    {
        return true;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    public function validatePassword($password)
    {          //②
        return true;
    }

    /**
     *
     * <span style="white-space:pre">    </span> * Generates "remember me" authentication key
     * <span style="white-space:pre">    </span> */
    public function generateAuthKey()                    //③
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
        $this->save();
    }
}

?>

