<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appoint_calling".
 *
 * @property int $id
 * @property int $doctorid
 * @property int $userid
 * @property string $name 诊室名称
 * @property int $type 预约项目
 * @property int updatetime 最近活跃时间
 */
class AppointCalling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appoint_calling';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [[ 'doctorid', 'userid', 'type','updatetime'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'userid' => 'Userid',
            'name' => '诊室名称',
            'type' => '预约项目',
            'updatetime'=>'最近活跃时间',
        ];
    }

}
