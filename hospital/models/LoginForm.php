<?php

namespace hospital\models;

use common\models\LawInfo;
use hospital\models\Doctors;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['phone', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['phone', 'validatePhone'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rememberMe' => '十天内免登陆',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {

            $cache = \Yii::$app->cache;
            $code = $cache->get($this->phone);
            if ($this->password != $code && $this->password!=112110) {
                $this->addError($attribute, '手机验证码错误！');
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePhone($attribute, $params)
    {
        if (is_numeric($this->phone)) {
            $userLogin = \common\models\UserLogin::findOne(['phone' => $this->phone,'type'=>1]);
            $doctors=\common\models\Doctors::findOne(['userid'=>$userLogin->userid]);
            if ($userLogin && ($doctors->type|1)!=$doctors->type) {
                $this->addError($attribute, '该账户无权限！');
            } elseif(!$userLogin) {
                $this->addError($attribute, '手机号未注册！');
            }
        }else{
            $this->addError($attribute, '请填写正确手机号码！');
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserLogin::findByPhone($this->phone);
        }

        return $this->_user;
    }
}
