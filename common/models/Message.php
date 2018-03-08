<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $userid
 * @property integer $usertype
 * @property integer $createtime
 * @property string $content
 * @property string $url
 * @property string $title
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'userid', 'usertype', 'createtime'], 'integer'],
            [['type','content', 'url', 'title'], 'required'],
            [['content', 'url'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'type' => '类型',
            'userid' => '发送人',
            'usertype' => '发送人类型',
            'createtime' => '发送时间',
            'content' => '通知内容',
            'url' => '通知链接',
            'title' => '通知标题',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            $this->createtime=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
