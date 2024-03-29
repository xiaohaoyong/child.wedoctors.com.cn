<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/29
 * Time: 下午4:13
 */

namespace databackend\models;


use common\models\DataUser;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['username', 'validateUsername'],
            [['verifyCode'], 'required'],
            ['verifyCode', 'captcha', 'message'=>'验证码错误'],
        ];
    }

    public function validateUsername($attribute, $params)
    {
        $dataUser=DataUser::findOne(['username'=>$this->username]);
        if($dataUser && $dataUser->type!=1)
        {
            $this->addError($attribute, '医院账号无法登陆监管后台，请访问hospital.child登陆');
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我',
            'verifyCode'=>'',

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
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
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

            $login = \Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

            if($login){
                $this->_user->token=\Yii::$app->session->getId();
                $this->_user->save();
            }
            return $login;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = \databackend\models\User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
