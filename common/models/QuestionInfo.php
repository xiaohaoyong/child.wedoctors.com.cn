<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_info".
 * 提问详情
 * @property int $qid
 * @property string $content
 */
class QuestionInfo extends \yii\db\ActiveRecord
{
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
            [['qid'], 'integer'],
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
        ];
    }
}
