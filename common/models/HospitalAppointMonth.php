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
        [2 => '2个月', 3 => '3个月'],
        [5 => '5个月', 6 => '6个月'],
        [8 => '8个月', 9 => '9个月'],
        [11 => '11个月', 12 => '12个月'],
        [17 => '1岁5个月', 18 => '1岁6个月'],
        [23 => '1岁11个月', 24 => '1岁12个月'],
        [29 => '2岁5个月', 30 => '2岁6个月'],
        [41 => '2岁11个月', 42 => '2岁12个月'],
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
