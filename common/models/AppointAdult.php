<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_adult".
 *
 * @property int $userid 用户主键
 * @property string $name 姓名
 * @property int $birthday 生日
 * @property int $gender 性别
 * @property int $createtime 添加时间
 * @property int $phone 联系电话
 */
class AppointAdult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_adult';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'name', 'gender', 'phone'], 'required'],
            [['userid', 'birthday', 'gender', 'createtime', 'phone'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['userid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userid' => '用户主键',
            'name' => '姓名',
            'birthday' => '生日',
            'gender' => '性别',
            'createtime' => '添加时间',
            'phone' => '联系电话',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
