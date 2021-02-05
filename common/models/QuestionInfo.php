<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_info".
 * 提问详情
 * @property int $qid
 * @property string $content
 * @property int $sex
 * @property int $birthday
 */
class QuestionInfo extends \yii\db\ActiveRecord
{
    public static $sexText=[1=>'男',2=>'女'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qid'], 'required'],
            [['qid','sex','birthday'], 'integer'],
            [['content'], 'string', 'max' => 200],
            [['qid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'qid' => '问题ID',
            'content' => '内容',
            'birthday'=>'生日',
            'sex'=>'性别'
        ];
    }
}
