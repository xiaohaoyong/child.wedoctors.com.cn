<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_naire".
 *
 * @property int $id
 * @property string $title
 * @property int $createtime
 */
class QuestionNaire extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question_naire';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime'], 'integer'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'createtime' => 'Createtime',
        ];
    }
}
