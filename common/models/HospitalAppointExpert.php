<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_expert".
 *
 * @property int $id
 * @property int $haid 管理主键
 * @property int $week
 * @property int $expert
 */
class HospitalAppointExpert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_expert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['haid', 'week', 'expert'], 'integer'],
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
            'expert' => 'Expert',
        ];
    }
}
