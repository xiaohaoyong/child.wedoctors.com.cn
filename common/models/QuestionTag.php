<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_tag".
 * 问题关联标签
 * @property int $id
 * @property int $tagid
 * @property int $qid
 * @property int $createtime
 */
class QuestionTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tagid', 'qid', 'createtime'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tagid' => '标签ID',
            'qid' => '问题ID',
            'createtime' => '创建时间',
        ];
    }
}
