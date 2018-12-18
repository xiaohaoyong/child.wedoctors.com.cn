<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_week".
 *
 * @property int $id
 * @property int $week
 * @property int $time_type
 * @property int $num
 * @property int $haid
 */
class HospitalAppointWeek extends \yii\db\ActiveRecord
{
    public static $typeText=[
        1=>'08：00-09：00',2=>'09：00-10：00',3=>'10：00-11：00',4=>'13：00-14：00',5=>'14：00-15：00',6=>'15：00-16：00'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_week';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['week', 'time_type', 'num', 'haid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'week' => 'Week',
            'time_type' => 'Time Type',
            'num' => 'Num',
            'haid' => 'Haid',
        ];
    }
}
