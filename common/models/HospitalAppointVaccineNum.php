<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_vaccine_num".
 *
 * @property int $id
 * @property int $vaccine
 * @property int $num
 * @property int $haid
 * @property int $week
 */
class HospitalAppointVaccineNum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_vaccine_num';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vaccine', 'num', 'haid', 'week'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vaccine' => 'Vaccine',
            'num' => 'Num',
            'haid' => 'Haid',
            'week' => 'Week',
        ];
    }
}
