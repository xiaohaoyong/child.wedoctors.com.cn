<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "login".
 *
 * @property int $id
 * @property int $userid 用户主键
 * @property string $access_token key
 * @property int $type 类型
 * @property int $createtime 创建时间
 * @property int $logintime 最近登录时间
 */
class Login extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid', 'type', 'createtime', 'logintime'], 'integer'],
            [['access_token'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '用户主键',
            'access_token' => 'key',
            'type' => '类型',
            'createtime' => '创建时间',
            'logintime' => '最近登录时间',
        ];
    }
}
