<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_parent}}".
 *
 * @property int $userid 用户主键
 * @property string $mother 母亲姓名
 * @property int $mother_phone 母亲手机号
 * @property string $mother_id 母亲身份证
 * @property string $father 父亲姓名
 * @property int $father_phone 父亲手机号
 * @property int $father_birthday 父亲生日
 * @property int $state 1,散居
 * @property string $address 详细地址
 * @property int $source 导入来源医院ID
 * @property string $field34 父亲文化程度
 * @property string $field33 父亲职业
 * @property string $field30 母亲文化程度
 * @property string $field29 母亲职业
 * @property string $field28 母亲出生日期
 * @property string $field12 联系人电话
 * @property string $field11 联系人姓名
 * @property string $field1 户口
 * @property integer $province
 * @property string $county
 * @property integer $city
 */
class UserParent extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_parent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
           // [['userid', 'mother', 'mother_phone', 'father'], 'required'],
            [['userid','province','county','city'], 'integer'],
        ];
    }

    //通过id获取信息 刘方露
    public static function GetInfoById($userid)
    {
        return UserParent::find()->where(['userid'=>$userid])->one();
    }

    public function attributeLabels() {
        return [
            'userid' => '用户主键',
            'mother' => '母亲姓名',
            'mother_phone' => '母亲手机号',
            'mother_id' => '母亲身份证',
            'father' => '父亲姓名',
            'father_phone' => '父亲手机号',
            'father_birthday' => '父亲生日',
            'state' => '1,散居',
            'address' => '详细地址',
            'source' => '导入来源医院ID',
            'field34' => '父亲文化程度',
            'field33' => '父亲职业',
            'field30' => '母亲文化程度',
            'field29' => '母亲职业',
            'field28' => '母亲出生日期',
            'field12' => '联系人电话',
            'field11' => '联系人姓名',
            'field1' => '户口',
            'province' => '省', 'county' => '县', 'city' => '市',
        ];
    }
    public function getChild()
    {
        return $this->hasMany(ChildInfo::className(),['userid'=>'userid']);
    }
}
