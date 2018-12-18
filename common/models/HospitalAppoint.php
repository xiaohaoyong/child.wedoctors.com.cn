<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint".
 *
 * @property int $id
 * @property int $doctorid
 * @property int $cycle
 * @property int $delay
 * @property int type
 */
class HospitalAppoint extends \yii\db\ActiveRecord
{
    public static $cycleText=[1=>'1周',2=>'2周',3=>'1个月'];
    public static $cycleNum=[1=>7,2=>14,3=>30];
    public static $typeText=[1=>'体检预约',2=>'疫苗预约',3=>'微量元素'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cycle','delay'], 'required'],
            [['doctorid', 'cycle', 'delay'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'doctorid' => 'Doctorid',
            'cycle' => 'Cycle',
            'delay' => 'Delay',
            'type' => 'Type',

        ];
    }
}
