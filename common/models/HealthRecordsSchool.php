<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "health_records_school".
 *
 * @property int $id
 * @property string $name 学校名称
 * @property int $doctorid 管辖社区
 * @property string $sign1 校医签字
 * @property string $sign2 医生签字
 * @property string $doctor_name 医生团队名称
 */
class HealthRecordsSchool extends \yii\db\ActiveRecord
{
    public static $typeSign=[
        'xiaoyi'=>'9346)1f?]q63',
        'doctor'=>'K1935.153f>-',
    ];
    public static $typeI=[
        'xiaoyi'=>1,
        'doctor'=>2,
    ];
    public static $typeA=[
        'xiaoyi'=>'sign1',
        'doctor'=>'sign2',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'health_records_school';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'doctorid'], 'integer'],
            [['name'], 'string', 'max' => 40],
            [['sign1', 'sign2'], 'string', 'max' => 100],
            [[ 'doctor_name','school_name','doctor_phone','school_phone','family_name'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '学校名称',
            'doctorid' => '管辖社区',
            'sign1' => '校医签字',
            'sign2' => '医生签字',
            'doctor_name' => '医生团队名称',
            'school_name' => '校医姓名',
            'doctor_phone' => '医生电话',
            'school_phone' => '校医电话',
            'nianji' => '年级',
            'family_name'=>'团队家庭医生姓名'

        ];
    }
}
