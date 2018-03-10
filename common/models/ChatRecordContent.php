<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chat_record_content".
 *
 * @property integer $id
 * @property integer $chatid
 * @property integer $content
 */
class ChatRecordContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_record_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chatid','content'], 'required'],
            [['id', 'chatid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'chatid' => '聊天记录主表ID',
            'content' => '聊天内容',
        ];
    }
}
