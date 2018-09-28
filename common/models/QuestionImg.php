<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_img".
 * 提问图片
 * @property int $id
 * @property string $image
 * @property int $qid
 */
class QuestionImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qid'], 'integer'],
            [['image'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => '图片地址',
            'qid' => '问题ID',
        ];
    }
}
