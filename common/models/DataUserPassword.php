<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/4
 * Time: 下午6:18
 */

namespace common\models;


class DataUserPassword extends DataUser
{
    public $password1;
    public $password2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'password1','password2'], 'required'],

            ['password','validatePassword','on'=>['update']],
            [['password1'],'match','pattern'=>'/^[A-Za-z0-9]{6,20}$/','message'=>'密码必须是大于6位，小于20位并且包含大写字母，小写字母，数字'],

            ['password2','compare','compareAttribute'=>'password1','message'=>'两次密码不一致'],
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
            $user = \databackend\models\User::findOne(\Yii::$app->user->id);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '原始密码错误!');
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password'=>'原始密码',
            'password1'=>'新密码',
            'password2'=>'确认新密码'
        ];
    }
}