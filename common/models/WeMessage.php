<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "we_message".
 *
 * @property int $id
 * @property string $ToUserName
 * @property string $FromUserName
 * @property int $CreateTime
 * @property string $MsgType
 * @property string $MsgId
 */
class WeMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'we_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CreateTime'], 'integer'],
            [['MsgId'], 'required'],
            [['ToUserName'], 'string', 'max' => 30],
            [['FromUserName'], 'string', 'max' => 50],
            [['MsgType'], 'string', 'max' => 10],
            [['MsgId'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ToUserName' => 'To User Name',
            'FromUserName' => 'From User Name',
            'CreateTime' => 'Create Time',
            'MsgType' => 'Msg Type',
            'MsgId' => 'Msg ID',
        ];
    }
}
