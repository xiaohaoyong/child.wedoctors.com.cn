<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "doctor_price".
 *医生价格表
 * @property int $doctorid
 * @property double $price
 */
class DoctorPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctorid'], 'required'],
            [['doctorid'], 'integer'],
            [['price'], 'number'],
            [['doctorid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctorid' => '医生ID',
            'price' => '价格',
        ];
    }
}
