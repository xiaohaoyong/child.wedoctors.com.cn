<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ask_chat_record_view".
 *
 * @property int $record_id
 * @property string $content
 */
class AskChatRecordView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ask_chat_record_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['record_id'], 'required'],
            [['record_id'], 'integer'],
            [['content'], 'string', 'max' => 200],
            [['record_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'record_id' => 'Rid',
            'content' => 'Content',
        ];
    }
}
