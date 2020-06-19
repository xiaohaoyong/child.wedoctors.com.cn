<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_naire_answer".
 *
 * @property int $id
 * @property int $answer
 * @property int $qnaid
 * @property int $userid
 */
class QuestionNaireAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_naire_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer', 'qnaid', 'userid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'answer' => 'Answer',
            'qnaid' => 'Qnaid',
            'userid' => 'Userid',
        ];
    }
}
