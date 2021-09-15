<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_vaccine".
 *
 * @property int $id
 * @property int $haid 管理主键
 * @property int $week
 * @property int $vaccine
 * @property int $type
 */
class HospitalAppointVaccine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_vaccine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type','haid', 'week', 'vaccine'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'haid' => '管理主键',
            'week' => 'Week',
            'vaccine' => 'Vaccine',
        ];
    }
}
