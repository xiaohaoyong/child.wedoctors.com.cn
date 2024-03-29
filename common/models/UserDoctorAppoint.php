<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_doctor_appoint".
 *
 * @property int $doctorid
 * @property int $weeks
 * @property int $cycle
 * @property int $delay
 * @property int $type
 * @property int $type1_num
 * @property int $type2_num
 * @property int $type3_num
 * @property int $type4_num
 * @property int $type5_num
 * @property int $type6_num
 */
class UserDoctorAppoint extends \yii\db\ActiveRecord
{
    public $week;
    public static $cycleText=[1=>'1周',2=>'2周',3=>'1个月'];
    public static $cycleNum=[1=>7,2=>14,3=>30];
    public static $typeText=[1=>'体检预约',2=>'疫苗预约',3=>'微量元素'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_doctor_appoint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctorid','week'], 'required'],
            [['doctorid', 'cycle', 'delay', 'type1_num', 'type2_num', 'type3_num', 'type4_num', 'type5_num', 'type6_num','type'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctorid' => '社区医院ID',
            'weeks' => '允许预约日期',
            'week'=>'允许预约日期',
            'cycle' => '周期长度',
            'delay' => '延迟日期',
            'type' => '类型',
            'type1_num' => '08：00-09：00预约人数',
            'type2_num' => '09：00-10：00预约人数',
            'type3_num' => '10：00-11：00预约人数',
            'type4_num' => '13：00-14：00预约人数',
            'type5_num' => '14：00-15：00预约人数',
            'type6_num' => '15：00-16：00预约人数',
        ];
    }
    public function beforeSave($insert)
    {
        if($this->week){
            $this->weeks=implode('',$this->week);
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
