<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ask_chat_record".
 *
 * @property int $id
 * @property int $rid 房间ID
 * @property int $userid 发送用户
 * @property int $touserid 接收用户
 * @property int $type 聊天内容
 * @property int $createtime 时间
 * @property int $is_read 是否已读
 */
class AskChatRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ask_chat_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rid', 'userid', 'touserid', 'createtime','is_read','type'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rid' => '房间ID',
            'userid' => '发送用户',
            'touserid' => '接收用户',
            'createtime' => '时间',
            'is_read' => '是否已读',
            'type' => '消息类型',

        ];
    }
    public function beforeSave($insert)
    {
        if($insert && !$this->createtime){
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
