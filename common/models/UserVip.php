<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_vip".
 * 用户vip
 * @property int $userid
 * @property int $starttime
 * @property int $endtime
 */
class UserVip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_vip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid', 'starttime', 'endtime'], 'integer'],
            [['userid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userid' => '用户',
            'starttime' => '开始时间',
            'endtime' => '结束时间',
        ];
    }
}
