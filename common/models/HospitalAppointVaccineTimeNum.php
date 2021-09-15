<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_vaccine_time_num".
 *
 * @property int $id
 * @property int $week
 * @property int $vaccine
 * @property int $num
 * @property int $type
 * @property int $doctorid
 * @property int $appoint_time
 */
class HospitalAppointVaccineTimeNum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_vaccine_time_num';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'week', 'vaccine', 'num', 'type', 'doctorid', 'appoint_time'], 'integer'],
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
            'vaccine' => 'Vaccine',
            'num' => 'Num',
            'type' => 'Type',
            'doctorid' => 'Doctorid',
            'appoint_time' => 'Appoint Time',
        ];
    }
}
