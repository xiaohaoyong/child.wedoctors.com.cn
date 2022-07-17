<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mp_event_push".
 *
 * @property int $id
 * @property string $openid openid
 * @property int $createtime 创建时间
 * @property string $event 事件类型
 * @property int $is_push 是否推送
 */
class MpEventPush extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mp_event_push';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime'], 'required'],
            [['createtime', 'is_push'], 'integer'],
            [['openid'], 'string', 'max' => 100],
            [['event'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'createtime' => 'Createtime',
            'event' => 'Event',
            'is_push' => 'Is Push',
        ];
    }
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->createtime = time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
