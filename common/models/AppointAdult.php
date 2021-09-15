<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_adult".
 * @property int $userid 用户主键
 * @property int $id 用户主键
 * @property string $name 姓名
 * @property int $birthday 生日
 * @property int $gender 性别
 * @property int $createtime 添加时间
 * @property int $phone 联系电话
 * @property int $source 来源
 * @property int $id_card 身份证
 * @property int $place 身份证
 */
class AppointAdult extends \yii\db\ActiveRecord
{
    public static $genderText=[1=>'男',2=>'女'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_adult';
    }
    public function scenarios()
    {
        return [
            'naire'=>['userid', 'name', 'gender','phone','id_card','place','birthday'],
            'lisc' => ['userid', 'name', 'gender','phone','id_card','place'],
            's' => ['userid', 'name', 'gender','phone','id_card'],

            'default'=>['userid', 'name', 'gender','phone'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'name', 'gender', 'phone'], 'required'],
            [['userid', 'gender', 'createtime', 'phone','source'], 'integer'],
            [['birthday'],'date'],
            [['name'], 'string', 'max' => 20],
            [['place'], 'string', 'max' => 255],
            [['id_card','place'],'required','on'=>['lisc','naire']],
            [['id_card'], 'common\helpers\IdcardValidator','on'=>['naire']],
            [['phone'], 'validatePhone'],
        ];
    }
    public function validateid_card($attribute, $params){
        preg_match('/^[1-9]\d{5}(19|20)\d{2}[01]\d[0123]\d\d{3}[xX\d]$/', $this->id_card, $arr);
        if(!$arr){
            $this->addError($attribute, '请输入正确身份证号码！');
        }

    }
    public function validatePhone($attribute, $params){
        preg_match('/^1[3456789]\d{9}$/', $this->phone, $arr);
        if(!$arr){
            $this->addError($attribute, '请输入正确手机号码！');
        }
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
            'id_card' => '身份证号',
            'place' => '户籍地址',

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
