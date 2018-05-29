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
    public function validateUsername($attribute, $params)
    {
        $dataUser=DataUser::findOne(['username',$this->username]);
        if($dataUser && $dataUser->type!=1)
        {
            $this->addError($attribute, '医院账号无法登陆监管后台，请访问hospital.child登陆');
        }
    }

}