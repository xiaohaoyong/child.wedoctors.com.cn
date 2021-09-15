<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_street".
 *
 * @property int $id
 * @property int $haid 管理主键
 * @property int $week
 * @property int $street
 */
class HospitalAppointStreet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_street';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['haid', 'week', 'street'], 'integer'],
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
            'week' => 'Week',
            'street' => 'street',
        ];
    }
}
