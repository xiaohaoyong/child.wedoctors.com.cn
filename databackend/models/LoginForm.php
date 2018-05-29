<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/29
 * Time: 下午4:13
 */

namespace databackend\models;


use common\models\DataUser;

class LoginForm extends \common\models\LoginForm
{
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

        ];
    }

    public function validateUsername($attribute, $params)
    {
        $dataUser=DataUser::findOne(['username',$this->username]);
        if($dataUser && $dataUser->type!=1)
        {
            $this->addError($attribute, '医院账号无法登陆监管后台，请访问hospital.child登陆');
        }
    }

}