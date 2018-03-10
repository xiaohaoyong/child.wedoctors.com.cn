<?php

namespace backend\models\rbac;

use Yii;
use backend\models\rbac\AuthAdminuser;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $userlogin;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['userlogin', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            
            ['password', 'validatePassword'],
        ];
    }
    
    public function login()
    {
        if($this->validate())
        {
            $user = $this->getUser();

            $duration = $this->rememberMe ? 3600*24*7 : 86400;

            return Yii::$app->user->login($user, $duration);
        }

        return false;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password))
            {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    public function getUser()
    {
        if ($this->_user === false)
        {
            $this->_user = AuthAdminuser::findByUsername($this->userlogin);
        }

        return $this->_user;
    }
}
