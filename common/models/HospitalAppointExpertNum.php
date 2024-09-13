<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_expert_num".
 *
 * @property int $id
 * @property int $expert
 * @property int $num
 * @property int $haid
 * @property int $week
 */
class HospitalAppointExpertNum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_expert_num';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert', 'num', 'haid', 'week'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expert' => 'Expert',
            'num' => 'Num',
            'haid' => 'Haid',
            'week' => 'Week',
        ];
    }
}
