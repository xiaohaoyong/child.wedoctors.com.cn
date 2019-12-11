<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ask_chat_room".
 *
 * @property int $id
 * @property int $userid 用户
 * @property int $doctorid 回复医生
 * @property int $createtime 创建时间
 * @property int $orderid 关联订单
 */
class AskChatRoom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ask_chat_room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'doctorid', 'createtime', 'orderid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '用户',
            'doctorid' => '回复医生',
            'createtime' => '创建时间',
            'orderid' => '关联订单',
        ];
    }
}
