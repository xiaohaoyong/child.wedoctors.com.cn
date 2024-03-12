<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_month".
 *
 * @property int $id
 * @property int $haid
 * @property int $type
 * @property int $month
 */
class HospitalAppointMonth extends \yii\db\ActiveRecord
{
    public static $typeText=[
        '满月体检',
        '3月龄体检',
        '5-6月龄体检',
        '8-9月龄体检',
        '12月龄体检',
        '18月龄体检',
        '24月龄体检',
        '2岁6月龄体检',
        '3岁体检',
    ];
    public static $monthText=[
        [1 => '满月'],
        [2 => '2个月', 3 => '3个月'],
        [5 => '5个月', 6 => '6个月'],
        [8 => '8个月', 9 => '9个月'],
        [11 => '11个月', 12 => '12个月'],
        [17 => '1岁5个月', 18 => '1岁6个月'],
        [23 => '1岁11个月', 24 => '1岁12个月'],
        [29 => '2岁5个月', 30 => '2岁6个月'],
        [35 => '2岁11个月', 36 => '2岁12个月', 37 => '3岁1个月'
        , 38 => '3岁2个月', 39 => '3岁3个月'
        , 40 => '3岁4个月', 41 => '3岁5个月'
        , 42 => '3岁6个月', 43 => '3岁7个月'
        , 44 => '3岁8个月', 45 => '3岁9个月'
        , 46 => '3岁10个月', 47 => '3岁11个月'
        , 48 => '3岁12个月'],
    ];
    public static $month=[
        1,2,5,8,11,17,23,29,35
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_month';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['haid', 'type', 'month'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'haid' => 'Haid',
            'type' => 'Type',
            'month' => 'Month',
        ];
    }
}
