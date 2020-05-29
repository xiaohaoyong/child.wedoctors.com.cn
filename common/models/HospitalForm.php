<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hospital_form".
 *
 * @property int $id
 * @property int $sign1 扫码签约数
 * @property int $sign2 签约签字数
 * @property int $date 统计日期
 * @property int $ratio1 当日整体签约率
 * @property int $ratio2 当日整体签字率
 * @property int $appoint_num 预约数
 * @property int $other_appoint_num 预约数（其他来源）
 * @property int $doctorid 社区
 */
class HospitalForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hospital_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sign1', 'sign2', 'sign3', 'date', 'ratio1', 'ratio2', 'appoint_num', 'other_appoint_num', 'doctorid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sign1' => '扫码签约数',
            'sign2' => '签约签字数',
            'sign3' => '签约孕妇',
            'date' => '统计日期',
            'ratio1' => '当日整体签约率',
            'ratio2' => '当日整体签字率',
            'appoint_num' => '预约数',
            'other_appoint_num' => '预约数（其他来源）',
            'doctorid' => '社区',
        ];
    }
}
