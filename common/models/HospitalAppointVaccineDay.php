<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_vaccine_day".
 *
 * @property int $id
 * @property int $haid
 * @property int $vaccine
 * @property int $day
 */
class HospitalAppointVaccineDay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_vaccine_day';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['haid', 'vaccine', 'day'], 'required'],
            [['haid', 'vaccine', 'day'], 'integer'],
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
            'vaccine' => 'Vaccine',
            'day' => 'Day',
        ];
    }
}
