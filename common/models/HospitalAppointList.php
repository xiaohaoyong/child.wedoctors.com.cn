<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_appoint_list".
 *
 * @property int $id
 * @property int $appoint_date 日期
 * @property int $doctorid 社区
 * @property int $haid 关联设置
 * @property int $appoint_time 时间段
 * @property int $num 预约数
 * @property int $vid 预约疫苗
 */
class HospitalAppointList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_appoint_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'appoint_date', 'doctorid', 'haid', 'appoint_time', 'num', 'vid'], 'integer'],
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
            'appoint_date' => '日期',
            'doctorid' => '社区',
            'haid' => '关联设置',
            'appoint_time' => '时间段',
            'num' => '预约数',
            'vid' => '预约疫苗',
        ];
    }
}
