<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint".
 *
 * @property int $id
 * @property int $userid
 * @property int $doctorid
 * @property int $createtime
 * @property int $appoint_time
 * @property int $appoint_date
 * @property int $type
 * @property int $childid
 * @property int $phone
 * @property int $state
 */
class Appoint extends \yii\db\ActiveRecord
{
    public static $timeText=[
        '1'=>'08：00-09：00',
        '2'=>'09：00-10：00',
        '3'=>'10：00-11：00',
        '4'=>'13：00-14：00',
        '5'=>'14：00-15：00',
        '6'=>'15：00-16：00',
    ];
    public static $stateText=[
        1=>'进行中',
        2=>'已完成',
        3=>'已取消',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'doctorid', 'createtime', 'appoint_time', 'appoint_date', 'type', 'childid', 'phone','state'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '父母',
            'doctorid' => '预约社区医院',
            'createtime' => '创建时间',
            'appoint_time' => '预约时间',
            'appoint_date' => '预约日期',
            'type' => '预约项目',
            'childid' => '儿童',
            'phone' => '手机号',
            'state'=>'预约状态'
        ];
    }
}
