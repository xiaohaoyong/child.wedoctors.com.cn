<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mother_form".
 *
 * @property int $id
 * @property string $name 真实姓名
 * @property int $phone 手机号
 * @property int $province 省
 * @property int $county 县
 * @property int $city 市
 * @property string $address 详细地址
 * @property string $idcard 身份证
 * @property string $date 预产期/出生日期
 * @property string $idimg 身份证照片
 * @property int $userid 用户唯一信息
 */
class MotherForm extends \yii\db\ActiveRecord
{
    public $year;
    public $month;
    public $day;
    public $img;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mother_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'address', 'idcard', 'date', 'idimg', 'userid'], 'required'],
            [['phone', 'province', 'county', 'city', 'userid'], 'integer'],
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100],
            [['idcard'], 'string', 'max' => 8,'min' => 8],
            [['idimg'], 'string', 'max' => 255],
            [['phone'],'match','pattern'=>'/^1[23456789]\d{9}$/'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '真实姓名',
            'phone' => '手机号',
            'province' => '省',
            'county' => '县',
            'city' => '市',
            'address' => '详细地址',
            'idcard' => '孕妈/宝妈本人身份证号后8位',
            'date' => '预产期/出生日期',
            'idimg' => '证件照片（身份证+证明照）',
            'userid' => '用户唯一信息',
            'year'=>'年份',
            'month'=>'月份',
            'day'=>'日期',
        ];
    }
}
